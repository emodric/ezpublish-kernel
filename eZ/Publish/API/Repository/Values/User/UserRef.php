<?php
/**
 * File containing the eZ\Publish\API\Repository\Values\User\UserRef interface.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

namespace eZ\Publish\API\Repository\Values\User;

/**
 *  This interface represents a user reference for use in sessions and Repository
 */
interface UserRef
{
    /**
     * The User id of the User this reference represent
     *
     * @return mixed
     */
    public function getUserId();
}
