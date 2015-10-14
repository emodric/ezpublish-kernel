<?php

/**
 * File containing the UrlAlias matcher class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased;

use eZ\Publish\Core\MVC\Symfony\View\LocationValueView;
use eZ\Publish\Core\MVC\Symfony\View\View;

class UrlAlias extends MultipleValued
{
    public function setMatchingConfig($matchingConfig)
    {
        if (!is_array($matchingConfig)) {
            $matchingConfig = array($matchingConfig);
        }

        array_walk(
            $matchingConfig,
            function (&$item) {
                $item = trim($item, '/ ');
            }
        );

        parent::setMatchingConfig($matchingConfig);
    }

    public function match(View $view)
    {
        if (!$view instanceof LocationValueView) {
            return false;
        }

        $location = $view->getLocation();
        $urlAliasService = $this->repository->getURLAliasService();
        $locationUrls = array_merge(
            $urlAliasService->listLocationAliases($location),
            $urlAliasService->listLocationAliases($location, false)
        );

        foreach ($this->values as $pattern => $val) {
            foreach ($locationUrls as $urlAlias) {
                if (strpos($urlAlias->path, "/$pattern") === 0) {
                    return true;
                }
            }
        }

        return false;
    }
}
