<?php

/**
 * File containing the ContentTypeGroup Id matcher class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id;

use eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\MultipleValued;
use eZ\Publish\Core\MVC\Symfony\View\ContentValueView;
use eZ\Publish\Core\MVC\Symfony\View\View;

class ContentTypeGroup extends MultipleValued
{
    public function match(View $view)
    {
        if (!$view instanceof ContentValueView) {
            return false;
        }

        $contentTypeGroups = $this->repository
            ->getContentTypeService()
            ->loadContentType($view->getContent()->contentInfo->contentTypeId)
            ->getContentTypeGroups();

        foreach ($contentTypeGroups as $group) {
            if (isset($this->values[$group->id])) {
                return true;
            }
        }

        return false;
    }
}
