<?php

/**
 * File containing the ParentLocationTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\MVC\Symfony\Matcher\Tests\ContentBased\Matcher\Id;

use eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id\ParentLocation as ParentLocationIdMatcher;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\Core\MVC\Symfony\Matcher\Tests\ContentBased\BaseTest;

class ParentLocationTest extends BaseTest
{
    /**
     * @var \eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id\ParentLocation
     */
    private $matcher;

    protected function setUp()
    {
        parent::setUp();
        $this->matcher = new ParentLocationIdMatcher();
    }

    /**
     * @dataProvider matchLocationValueViewProvider
     * @covers eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id\ParentLocation::match
     * @covers eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\MultipleValued::setMatchingConfig
     *
     * @param int|int[] $matchingConfig
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
                123,
                $this->getLocationMock(array('parentLocationId' => 123)),
                true,
            ),
            array(
                123,
                $this->getLocationMock(array('parentLocationId' => 456)),
                false,
            ),
            array(
                array(123, 789),
                $this->getLocationMock(array('parentLocationId' => 456)),
                false,
            ),
            array(
                array(123, 789),
                $this->getLocationMock(array('parentLocationId' => 789)),
                true,
            ),
        );
    }

    /**
     * @covers eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id\ParentLocation::match
     * @covers eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\MultipleValued::setMatchingConfig
     */
    public function testMatchContentValueView()
    {
        $this->assertSame(
            false,
            $this->matcher->match($this->getContentValueViewMock($this->getContentMock($this->getContentInfoMock())))
        );
    }
}
