<?php

/**
 * File containing the Section Id matcher class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id;

use eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\MultipleValued;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use eZ\Publish\Core\MVC\Symfony\View\View;

class Section extends MultipleValued
{
    public function match(View $view)
    {
        if (!$view instanceof ContentView) {
            return false;
        }

        return isset($this->values[$view->getContent()->contentInfo->sectionId]);
    }
}
