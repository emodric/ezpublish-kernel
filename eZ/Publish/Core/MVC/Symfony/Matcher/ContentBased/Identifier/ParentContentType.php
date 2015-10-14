<?php

/**
 * File containing the ParentContentType Identifier matcher class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Identifier;

use eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\MultipleValued;
use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\Values\Content\Location as APILocation;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\Core\MVC\Symfony\View\ContentValueView;
use eZ\Publish\Core\MVC\Symfony\View\LocationValueView;
use eZ\Publish\Core\MVC\Symfony\View\View;

class ParentContentType extends MultipleValued
{
    /**
     * Checks if a Location object matches.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Location $location
     *
     * @return bool
     */
    protected function matchLocation(APILocation $location)
    {
        $parentContentType = $this->repository->sudo(
            function (Repository $repository) use ($location) {
                $parent = $repository->getLocationService()->loadLocation($location->parentLocationId);

                return $repository
                    ->getContentTypeService()
                    ->loadContentType($parent->getContentInfo()->contentTypeId);
            }
        );

        return isset($this->values[$parentContentType->identifier]);
    }

    public function match(View $view)
    {
        if ($view instanceof LocationValueView) {
            return $this->matchLocation($view->getLocation());
        }

        if ($view instanceof ContentValueView) {
            $contentInfo = $view->getContent()->contentInfo;
            $location = $this->repository->sudo(
                function (Repository $repository) use ($contentInfo) {
                    return $repository->getLocationService()->loadLocation($contentInfo->mainLocationId);
                }
            );

            return $this->matchLocation($location);
        }

        return false;
    }
}
