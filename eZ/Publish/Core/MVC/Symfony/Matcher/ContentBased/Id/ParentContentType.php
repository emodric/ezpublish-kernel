<?php

/**
 * File containing the ParentContentType Id matcher class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id;

use eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\MultipleValued;
use eZ\Publish\API\Repository\Repository;
use eZ\Publish\Core\MVC\Symfony\View\LocationValueView;
use eZ\Publish\Core\MVC\Symfony\View\View;

class ParentContentType extends MultipleValued
{
    public function match(View $view)
    {
        if (!$view instanceof LocationValueView) {
            return false;
        }
        $parent = $this->loadParentLocation(
            $view->getLocation()->parentLocationId
        );

        return isset($this->values[$parent->getContentInfo()->contentTypeId]);
    }

    /**
     * @param mixed $locationId
     *
     * @return Location
     */
    private function loadParentLocation($locationId)
    {
        return $this->repository->sudo(
            function (Repository $repository) use ($locationId) {
                return $repository->getLocationService()->loadLocation($locationId);
            }
        );
    }
}
