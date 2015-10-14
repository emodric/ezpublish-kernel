<?php

/**
 * File containing the BaseTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\MVC\Symfony\Matcher\Tests\Block;

use eZ\Publish\Core\FieldType\Page\Parts\Block;
use PHPUnit_Framework_TestCase;

abstract class BaseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @param \eZ\Publish\Core\FieldType\Page\Parts\Block $block
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getBlockViewMock(Block $block)
    {
        $mock = $this
            ->getMock('eZ\\Publish\\Core\\MVC\\Symfony\\View\\BlockView');

        $mock
            ->expects($this->any())
            ->method('getBlock')
            ->will($this->returnValue($block));

        return $mock;
    }
}
