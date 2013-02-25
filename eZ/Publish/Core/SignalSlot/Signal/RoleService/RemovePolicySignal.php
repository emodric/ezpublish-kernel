<?php
/**
 * RemovePolicySignal class
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\SignalSlot\Signal\RoleService;

use eZ\Publish\Core\SignalSlot\Signal;

/**
 * RemovePolicySignal class
 * @package eZ\Publish\Core\SignalSlot\Signal\RoleService
 */
class RemovePolicySignal extends Signal
{
    /**
     * RoleId
     *
     * @var mixed
     */
    public $roleId;

    /**
     * PolicyId
     *
     * @var mixed
     */
    public $policyId;
}
