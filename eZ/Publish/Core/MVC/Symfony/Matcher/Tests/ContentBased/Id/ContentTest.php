<?php

/**
 * File containing the ContentTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\MVC\Symfony\Matcher\Tests\ContentBased\Matcher\Id;

use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id\Content as ContentIdMatcher;
use eZ\Publish\Core\MVC\Symfony\Matcher\Tests\ContentBased\BaseTest;

class ContentTest extends BaseTest
{
    /**
     * @var \eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id\Content
     */
    private $matcher;

    protected function setUp()
    {
        parent::setUp();
        $this->matcher = new ContentIdMatcher();
    }

    /**
     * @dataProvider matchContentViewProvider
     * @covers eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Id\Content::match
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
                $this->getContentInfoMock(array('id' => 123)),
                true,
            ),
            array(
                123,
                $this->getContentInfoMock(array('id' => 456)),
                false,
            ),
            array(
                array(123, 789),
                $this->getContentInfoMock(array('id' => 456)),
                false,
            ),
            array(
                array(123, 789),
                $this->getContentInfoMock(array('id' => 789)),
                true,
            ),
        );
    }
}
