<?php

/**
 * File containing the Section identifier matcher class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Identifier;

use eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\MultipleValued;
use eZ\Publish\API\Repository\Repository;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use eZ\Publish\Core\MVC\Symfony\View\View;

class Section extends MultipleValued
{
    public function match(View $view)
    {
        if (!$view instanceof ContentView) {
            return false;
        }

        $contentInfo = $view->getContent()->contentInfo;
        $section = $this->repository->sudo(
            function (Repository $repository) use ($contentInfo) {
                return $repository->getSectionService()->loadSection(
                    $contentInfo->sectionId
                );
            }
        );

        return isset($this->values[$section->identifier]);
    }
}
