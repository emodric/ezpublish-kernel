<?php

/**
 * File containing the LocationTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\MVC\Symfony\Matcher\Tests\ContentBased\Matcher\Id;

use eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id\Location as LocationIdMatcher;
use eZ\Publish\Core\MVC\Symfony\Matcher\Tests\ContentBased\BaseTest;
use eZ\Publish\Core\MVC\Symfony\View\LocationValueView;

class LocationTest extends BaseTest
{
    /**
     * @var \eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id\Location
     */
    private $matcher;

    protected function setUp()
    {
        parent::setUp();
        $this->matcher = new LocationIdMatcher();
    }

    /**
     * @dataProvider matchLocationValueViewProvider
     * @covers eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id\Location::match
     * @covers eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\MultipleValued::setMatchingConfig
     *
     * @param int|int[] $matchingConfig
     * @param \eZ\Publish\Core\MVC\Symfony\View\LocationValueView $view
     * @param bool $expectedResult
     */
    public function testMatchLocationValueView($matchingConfig, LocationValueView $view, $expectedResult)
    {
        $this->matcher->setMatchingConfig($matchingConfig);
        $this->assertSame($expectedResult, $this->matcher->match($view));
    }

    public function matchLocationValueViewProvider()
    {
        return array(
            array(
                123,
                $this->getLocationValueViewMock($this->getLocationMock(array('id' => 123))),
                true,
            ),
            array(
                123,
                $this->getLocationValueViewMock($this->getLocationMock(array('id' => 456))),
                false,
            ),
            array(
                array(123, 789),
                $this->getLocationValueViewMock($this->getLocationMock(array('id' => 456))),
                false,
            ),
            array(
                array(123, 789),
                $this->getLocationValueViewMock($this->getLocationMock(array('id' => 789))),
                true,
            ),
        );
    }

    /**
     * @covers eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id\Location::match
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
