<?php

/**
 * File containing the SectionTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\MVC\Symfony\Matcher\Tests\ContentBased\Matcher\Identifier;

use eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Identifier\Section as SectionIdentifierMatcher;
use eZ\Publish\Core\MVC\Symfony\Matcher\Tests\ContentBased\BaseTest;
use eZ\Publish\API\Repository\Repository;

class SectionTest extends BaseTest
{
    /**
     * @var \eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Identifier\Section
     */
    private $matcher;

    protected function setUp()
    {
        parent::setUp();
        $this->matcher = new SectionIdentifierMatcher();
    }

    /**
     * Returns a Repository mock configured to return the appropriate Section object with given section identifier.
     *
     * @param string $sectionIdentifier
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function generateRepositoryMockForSectionIdentifier($sectionIdentifier)
    {
        $sectionServiceMock = $this
            ->getMockBuilder('eZ\\Publish\\API\\Repository\\SectionService')
            ->disableOriginalConstructor()
            ->getMock();
        $sectionServiceMock->expects($this->once())
            ->method('loadSection')
            ->will(
                $this->returnValue(
                    $this
                        ->getMockBuilder('eZ\\Publish\\API\\Repository\\Values\\Content\\Section')
                        ->setConstructorArgs(
                            array(
                                array('identifier' => $sectionIdentifier),
                            )
                        )
                        ->getMockForAbstractClass()
                )
            );

        $repository = $this->getRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('getSectionService')
            ->will($this->returnValue($sectionServiceMock));

        return $repository;
    }

    public function matchContentViewProvider()
    {
        return array(
            array(
                'foo',
                $this->generateRepositoryMockForSectionIdentifier('foo'),
                true,
            ),
            array(
                'foo',
                $this->generateRepositoryMockForSectionIdentifier('bar'),
                false,
            ),
            array(
                array('foo', 'baz'),
                $this->generateRepositoryMockForSectionIdentifier('bar'),
                false,
            ),
            array(
                array('foo', 'baz'),
                $this->generateRepositoryMockForSectionIdentifier('baz'),
                true,
            ),
        );
    }

    /**
     * @dataProvider matchContentValueViewProvider
     * @covers eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Identifier\Section::match
     * @covers eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\MultipleValued::setMatchingConfig
     * @covers \eZ\Publish\Core\MVC\RepositoryAware::setRepository
     *
     * @param string|string[] $matchingConfig
     * @param \eZ\Publish\API\Repository\Repository $repository
     * @param bool $expectedResult
     */
    public function testMatchContentView($matchingConfig, Repository $repository, $expectedResult)
    {
        $this->matcher->setRepository($repository);
        $this->matcher->setMatchingConfig($matchingConfig);

        $this->assertSame(
            $expectedResult,
            $this->matcher->match($this->getContentViewMock($this->getContentMock($this->getContentInfoMock())))
        );
    }
}
