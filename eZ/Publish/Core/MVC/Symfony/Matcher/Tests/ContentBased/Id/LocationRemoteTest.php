<?php

/**
 * File containing the RemoteTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\MVC\Symfony\Matcher\Tests\ContentBased\Matcher\Id;

use eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id\LocationRemote as LocationRemoteIdMatcher;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\Core\MVC\Symfony\Matcher\Tests\ContentBased\BaseTest;

class LocationRemoteTest extends BaseTest
{
    /**
     * @var \eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id\Remote
     */
    private $matcher;

    protected function setUp()
    {
        parent::setUp();
        $this->matcher = new LocationRemoteIdMatcher();
    }

    /**
     * @dataProvider matchLocationValueViewProvider
     * @covers eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id\LocationRemote::match
     * @covers eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\MultipleValued::setMatchingConfig
     *
     * @param string|string[] $matchingConfig
     * @param \eZ\Publish\API\Repository\Values\Content\Location $location
     * @param bool $expectedResult
     */
    public function testMatchLocationValueView($matchingConfig, Location $location, $expectedResult)
    {
        $this->matcher->setMatchingConfig($matchingConfig);
        $this->assertSame($expectedResult, $this->matcher->match($this->getLocationValueViewMock($location)));
    }

    public function matchLocationValueViewProvider()
    {
        return array(
            array(
                'foo',
                $this->getLocationMock(array('remoteId' => 'foo')),
                true,
            ),
            array(
                'foo',
                $this->getLocationMock(array('remoteId' => 'bar')),
                false,
            ),
            array(
                array('foo', 'baz'),
                $this->getLocationMock(array('remoteId' => 'bar')),
                false,
            ),
            array(
                array('foo', 'baz'),
                $this->getLocationMock(array('remoteId' => 'baz')),
                true,
            ),
        );
    }
}
