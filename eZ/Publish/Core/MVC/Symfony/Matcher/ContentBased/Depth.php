<?php

/**
 * File containing the Depth matcher class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased;

use eZ\Publish\API\Repository\Repository;
use eZ\Publish\Core\MVC\Symfony\View\ContentValueView;
use eZ\Publish\Core\MVC\Symfony\View\LocationValueView;
use eZ\Publish\Core\MVC\Symfony\View\View;

class Depth extends MultipleValued
{
    public function match(View $view)
    {
        if ($view instanceof LocationValueView) {
            return isset($this->values[$view->getLocation()->depth]);
        }

        if ($view instanceof ContentValueView) {
            $mainLocationId = $view->getContent()->contentInfo->mainLocationId;
            $location = $this->repository->sudo(
                function (Repository $repository) use ($mainLocationId) {
                    return $repository->getLocationService()->loadLocation($mainLocationId);
                }
            );

            return isset($this->values[$location->depth]);
        }

        return false;
    }
}
