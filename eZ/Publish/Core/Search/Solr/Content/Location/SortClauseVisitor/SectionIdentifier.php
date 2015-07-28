<?php
/**
 * File containing the SortClauseVisitor\SectionIdentifier class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */
namespace eZ\Publish\Core\Search\Solr\Content\Location\SortClauseVisitor;

use eZ\Publish\Core\Search\Solr\Content\SortClauseVisitor;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;

/**
 * Visits the sortClause tree into a Solr query.
 */
class SectionIdentifier extends SortClauseVisitor
{
    /**
     * CHeck if visitor is applicable to current sortClause.
     *
     * @param SortClause $sortClause
     *
     * @return bool
     */
    public function canVisit(SortClause $sortClause)
    {
        return $sortClause instanceof SortClause\SectionIdentifier;
    }

    /**
     * Map field value to a proper Solr representation.
     *
     * @param SortClause $sortClause
     *
     * @return string
     */
    public function visit(SortClause $sortClause)
    {
        return 'content_section_identifier_id' . $this->getDirection($sortClause);
    }
}
