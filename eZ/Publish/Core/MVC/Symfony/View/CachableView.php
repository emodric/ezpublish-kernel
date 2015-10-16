<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace eZ\Publish\Core\MVC\Symfony\View;

/**
 * A view that can be cached over HTTP.
 *
 * Should allow
 */
interface CachableView
{
    /**
     * Sets the cache as enabled/disabled.
     *
     * @param bool $cacheEnabled
     */
    public function setCacheEnabled($cacheEnabled);

    /**
     * Indicates if cache is enabled or not.
     *
     * @return bool
     */
    public function isCacheEnabled();

    /**
     * Returns max age value.
     *
     * @return int
     */
    public function getMaxAge();

    /**
     * Sets the max age value.
     *
     * @param int $maxAge
     */
    public function setMaxAge($maxAge);

    /**
     * Returns shared max age value.
     *
     * @return int
     */
    public function getSharedMaxAge();

    /**
     * Sets the shared max age value.
     *
     * @param int $sharedMaxAge
     */
    public function setSharedMaxAge($sharedMaxAge);
}
