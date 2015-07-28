<?php
/**
 * File containing the Price class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */
namespace eZ\Publish\Core\FieldType\Price;

use eZ\Publish\SPI\Persistence\Content\Field;
use eZ\Publish\SPI\FieldType\Indexable;
use eZ\Publish\SPI\Search;

/**
 * Indexable definition for string field type.
 */
class SearchField implements Indexable
{
    /**
     * Get index data for field for search backend.
     *
     * @param Field $field
     *
     * @return \eZ\Publish\SPI\Search\Field[]
     */
    public function getIndexData(Field $field)
    {
        return array(
            new Search\Field(
                'value',
                // @todo: Data is yet empty, this seems wrong, so we use the
                // sort field for now
                $field->value->sortKey['sort_key_int'] / 1000,
                new Search\FieldType\PriceField()
            ),
        );
    }

    /**
     * Get index field types for search backend.
     *
     * @return \eZ\Publish\SPI\Search\FieldType[]
     */
    public function getIndexDefinition()
    {
        return array(
            'value' => new Search\FieldType\PriceField(),
        );
    }

    /**
     * Get name of the default field to be used for query and sort.
     *
     * As field types can index multiple fields (see MapLocation field type's
     * implementation of this interface), this method is used to define default
     * field for query and sort. Default field is typically used by Field
     * criterion and sort clause.
     *
     * @return string
     */
    public function getDefaultField()
    {
        return 'value';
    }
}
