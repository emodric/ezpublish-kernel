<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace eZ\Publish\SPI\Search;

/**
 * Capability interface for search engines needed for {@see \eZ\Publish\API\Repository\SearchService::supports()}.
 *
 * @since 6.12 And ported to 6.7.6 for search engine forward compatibility.
 */
interface Capable
{
    /**
     * Query for supported capability of currently configured search engine.
     *
     * @param int $capabilityFlag One of eZ\Publish\API\Repository\SearchService::CAPABILITY_* constants.
     *
     * @return bool
     */
    public function supports(int $capabilityFlag): bool;
}
