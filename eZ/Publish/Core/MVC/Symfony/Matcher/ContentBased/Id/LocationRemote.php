<?php

/**
 * File containing the Remote matcher class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id;

use eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\MultipleValued;
use eZ\Publish\Core\MVC\Symfony\View\LocationValueView;
use eZ\Publish\Core\MVC\Symfony\View\View;

class LocationRemote extends MultipleValued
{
    public function match(View $view)
    {
        if (!$view instanceof LocationValueView) {
            return false;
        }

        return isset($this->values[$view->getLocation()->remoteId]);
    }
}
