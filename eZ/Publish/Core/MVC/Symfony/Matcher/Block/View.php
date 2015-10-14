<?php

/**
 * File containing the View block matcher class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\MVC\Symfony\Matcher\Block;

use eZ\Publish\Core\MVC\Symfony\View\BlockValueView;
use eZ\Publish\Core\MVC\Symfony\View\View as ViewObject;

class View extends MultipleValued
{
    public function match(ViewObject $view)
    {
        if (!$view instanceof BlockValueView) {
            return false;
        }

        return isset($this->values[$view->getBlock()->view]);
    }
}
