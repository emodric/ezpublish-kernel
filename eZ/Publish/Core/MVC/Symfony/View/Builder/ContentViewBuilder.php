<?php

/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace eZ\Publish\Core\MVC\Symfony\View\Builder;

use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Values\Content\VersionInfo;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentException;
use eZ\Publish\Core\Base\Exceptions\UnauthorizedException;
use eZ\Publish\Core\MVC\Symfony\View\Configurator;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use eZ\Publish\Core\MVC\Symfony\Security\Authorization\Attribute as AuthorizationAttribute;
use eZ\Publish\Core\MVC\Symfony\View\ParametersInjector;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Builds ContentView objects.
 */
class ContentViewBuilder implements ViewBuilder
{
    /** @var \eZ\Publish\API\Repository\Repository */
    private $repository;

    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    /** @var \eZ\Publish\Core\MVC\Symfony\View\Configurator */
    private $viewConfigurator;

    /** @var \eZ\Publish\Core\MVC\Symfony\View\ParametersInjector */
    private $viewParametersInjector;

    public function __construct(
        Repository $repository,
        AuthorizationCheckerInterface $authorizationChecker,
        Configurator $viewConfigurator,
        ParametersInjector $viewParametersInjector
    ) {
        $this->repository = $repository;
        $this->authorizationChecker = $authorizationChecker;
        $this->viewConfigurator = $viewConfigurator;
        $this->viewParametersInjector = $viewParametersInjector;
    }

    public function matches($argument)
    {
        return strpos($argument, 'ez_content:') !== false;
    }

    /**
     * @param array $parameters
     *
     * @return \eZ\Publish\Core\MVC\Symfony\View\ContentView|\eZ\Publish\Core\MVC\Symfony\View\View
     *         If both contentId and locationId parameters are missing
     * @throws \eZ\Publish\Core\Base\Exceptions\InvalidArgumentException
     *         If both contentId and locationId parameters are missing
     * @throws \eZ\Publish\Core\Base\Exceptions\UnauthorizedException
     */
    public function buildView(array $parameters)
    {
        $view = new ContentView(null, [], $parameters['viewType']);
        $view->setIsEmbed($this->isEmbed($parameters));

        if (isset($parameters['locationId'])) {
            $location = $this->loadLocation($parameters['locationId']);
        } elseif (isset($parameters['location'])) {
            $location = $parameters['location'];
        } else {
            $location = null;
        }

        if (isset($parameters['content'])) {
            $content = $parameters['content'];
        } else {
            if (isset($parameters['contentId'])) {
                $contentId = $parameters['contentId'];
            } elseif (isset($location)) {
                $contentId = $location->contentId;
            } else {
                throw new InvalidArgumentException('Content', 'No content could be loaded from parameters');
            }

            $content = $view->isEmbed() ? $this->loadContent($contentId) : $this->loadEmbeddedContent($contentId, $location);
        }

        $view->setContent($content);
        if (isset($location)) {
            $view->setLocation($location);
        }

        $this->viewConfigurator->configure($view);

        // viewLocation/embedLocation without a custom controller are mapped to their view equivalent
        if ($parameters['_controller'] === 'ez_content:viewLocation') {
            $view->setControllerReference(new ControllerReference('ez_content:viewAction'));
        } elseif ($parameters['_controller'] === 'ez_content:embedLocation') {
            $view->setControllerReference(new ControllerReference('ez_content:embedAction'));
        }

        $this->viewParametersInjector->injectViewParameters($view, $parameters);

        return $view;
    }

    /**
     * Loads Content with id $contentId.
     *
     * @param mixed $contentId
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Content
     *
     * @throws \eZ\Publish\Core\Base\Exceptions\UnauthorizedException
     */
    private function loadContent($contentId)
    {
        return $this->repository->getContentService()->loadContent($contentId);
    }

    /**
     * Loads the embedded content with id $contentId.
     * Will load the content with sudo(), and check if the user can view_embed this content, for the given location
     * if provided.
     *
     * @param mixed $contentId
     * @param \eZ\Publish\API\Repository\Values\Content\Location $location
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Content
     * @throws \eZ\Publish\Core\Base\Exceptions\UnauthorizedException
     */
    private function loadEmbeddedContent($contentId, Location $location = null)
    {
        $content = $this->repository->sudo(
            function (Repository $repository) use ($contentId) {
                return $repository->getContentService()->loadContent($contentId);
            }
        );

        if (!$this->canRead($content, $location)) {
            throw new UnauthorizedException(
                'content', 'read|view_embed',
                ['contentId' => $contentId, 'locationId' => $location !== null ? $location->id : 'n/a']
            );
        }

        // Check that Content is published, since sudo allows loading unpublished content.
        if (
            $content->getVersionInfo()->status !== VersionInfo::STATUS_PUBLISHED
            && !$this->authorizationChecker->isGranted(
                new AuthorizationAttribute('content', 'versionread', array('valueObject' => $content))
            )
        ) {
            throw new UnauthorizedException('content', 'versionread', ['contentId' => $contentId]);
        }

        return $content;
    }

    /**
     * Loads a visible Location.
     * @todo Do we need to handle permissions here ?
     *
     * @param $locationId
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Location
     */
    private function loadLocation($locationId)
    {
        $location = $this->repository->sudo(
            function (Repository $repository) use ($locationId) {
                return $repository->getLocationService()->loadLocation($locationId);
            }
        );
        if ($location->invisible) {
            throw new NotFoundHttpException('Location cannot be displayed as it is flagged as invisible.');
        }

        return $location;
    }

    /**
     * Checks if a user can read a content, or view it as an embed.
     *
     * @param Content $content
     * @param $location
     *
     * @return bool
     */
    private function canRead(Content $content, Location $location = null)
    {
        $limitations = ['valueObject' => $content->contentInfo];
        if (isset($location)) {
            $limitations['targets'] = $location;
        }

        $readAttribute = new AuthorizationAttribute('content', 'read', $limitations);
        $viewEmbedAttribute = new AuthorizationAttribute('content', 'view_embed', $limitations);

        return
            $this->authorizationChecker->isGranted($readAttribute) ||
            $this->authorizationChecker->isGranted($viewEmbedAttribute);
    }

    /**
     * Checks if the view is an embed one.
     * Uses either the controller action (embedAction), or the viewType (embed/embed-inline).
     *
     * @param array $parameters The ViewBuilder parameters array.
     *
     * @return bool
     */
    private function isEmbed($parameters)
    {
        if ($parameters['_controller'] === 'ez_content:embedAction') {
            return true;
        }
        if (in_array($parameters['viewType'], ['embed', 'embed-inline'])) {
            return true;
        }

        return false;
    }
}
