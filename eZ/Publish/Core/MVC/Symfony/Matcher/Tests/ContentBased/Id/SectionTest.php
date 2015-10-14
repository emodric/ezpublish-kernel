<?php

/**
 * File containing the SectionTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\MVC\Symfony\Matcher\Tests\ContentBased\Matcher\Id;

use eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id\Section as SectionIdMatcher;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\Core\MVC\Symfony\Matcher\Tests\ContentBased\BaseTest;

class SectionTest extends BaseTest
{
    /**
     * @var \eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id\Section
     */
    private $matcher;

    protected function setUp()
    {
        parent::setUp();
        $this->matcher = new SectionIdMatcher();
    }

    /**
     * @dataProvider matchContentViewProvider
     * @covers eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id\Section::match
     * @covers eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\MultipleValued::setMatchingConfig
     *
     * @param int|int[] $matchingConfig
     * @param \eZ\Publish\API\Repository\Values\Content\ContentInfo $contentInfo
     * @param bool $expectedResult
     */
    public function testMatchContentView($matchingConfig, ContentInfo $contentInfo, $expectedResult)
    {
        $this->matcher->setMatchingConfig($matchingConfig);
        $this->assertSame($expectedResult, $this->matcher->match($this->getContentViewMock($this->getContentMock($contentInfo))));
    }

    public function matchContentViewProvider()
    {
        return array(
            array(
                123,
                $this->getContentInfoMock(array('sectionId' => 123)),
                true,
            ),
            array(
                123,
                $this->getContentInfoMock(array('sectionId' => 456)),
                false,
            ),
            array(
                array(123, 789),
                $this->getContentInfoMock(array('sectionId' => 456)),
                false,
            ),
            array(
                array(123, 789),
                $this->getContentInfoMock(array('sectionId' => 789)),
                true,
            ),
        );
    }
}
