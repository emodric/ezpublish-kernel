<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace eZ\Publish\Core\MVC\Symfony\Matcher;

use eZ\Publish\Core\MVC\Symfony\Matcher\Block\MatcherInterface;
use eZ\Publish\Core\MVC\Symfony\View\View;

/**
 * Matches a View against a set of matchers.
 */
interface ViewMatcherInterface extends MatcherInterface
{
    /**
     * Matches the $view against a set of matchers
     */
    public function match(View $view);
}
