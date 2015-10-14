<?php

/**
 * File containing the ContentTypeTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\MVC\Symfony\Matcher\Tests\ContentBased\Matcher\Identifier;

use eZ\Publish\Core\MVC\Symfony\Matcher\Tests\ContentBased\BaseTest;
use eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Identifier\ContentType as ContentTypeIdentifierMatcher;
use eZ\Publish\API\Repository\Repository;

class ContentTypeTest extends BaseTest
{
    /**
     * @var \eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Identifier\ContentType
     */
    private $matcher;

    protected function setUp()
    {
        parent::setUp();
        $this->matcher = new ContentTypeIdentifierMatcher();
    }

    /**
     * @covers eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Identifier\ContentType::match
     * @covers eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\MultipleValued::setMatchingConfig
     */
    public function testMatchLocationValueView()
    {
        $this->assertSame(
            false,
            $this->matcher->match($this->getLocationValueViewMock($this->getLocationMock()))
        );
    }

    /**
     * @dataProvider matchContentValueViewProvider
     * @covers eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\Identifier\ContentType::match
     * @covers eZ\Publish\Core\MVC\Symfony\Matcher\ContentBased\MultipleValued::setMatchingConfig
     *
     * @param string|string[] $matchingConfig
     * @param \eZ\Publish\API\Repository\Repository $repository
     * @param bool $expectedResult
     */
    public function testMatchContentValueView($matchingConfig, Repository $repository, $expectedResult)
    {
        $this->matcher->setRepository($repository);
        $this->matcher->setMatchingConfig($matchingConfig);

        $this->assertSame(
            $expectedResult,
            $this->matcher->match(
                $this->getContentValueViewMock($this->getContentMock($this->getContentInfoMock(array('contentTypeId' => 42))))
            )
        );
    }

    public function matchContentValueViewProvider()
    {
        $data = array();

        $data[] = array(
            'foo',
            $this->generateRepositoryMockForContentTypeIdentifier('foo'),
            true,
        );

        $data[] = array(
            'foo',
            $this->generateRepositoryMockForContentTypeIdentifier('bar'),
            false,
        );

        $data[] = array(
            array('foo', 'baz'),
            $this->generateRepositoryMockForContentTypeIdentifier('bar'),
            false,
        );

        $data[] = array(
            array('foo', 'baz'),
            $this->generateRepositoryMockForContentTypeIdentifier('baz'),
            true,
        );

        return $data;
    }

    /**
     * Returns a Repository mock configured to return the appropriate ContentType object with given identifier.
     *
     * @param int $contentTypeIdentifier
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function generateRepositoryMockForContentTypeIdentifier($contentTypeIdentifier)
    {
        $contentTypeMock = $this
            ->getMockBuilder('eZ\\Publish\\API\\Repository\\Values\\ContentType\\ContentType')
            ->setConstructorArgs(
                array(array('identifier' => $contentTypeIdentifier))
            )
            ->getMockForAbstractClass();
        $contentTypeServiceMock = $this
            ->getMockBuilder('eZ\\Publish\\API\\Repository\\ContentTypeService')
            ->disableOriginalConstructor()
            ->getMock();
        $contentTypeServiceMock->expects($this->once())
            ->method('loadContentType')
            ->with(42)
            ->will(
                $this->returnValue($contentTypeMock)
            );

        $repository = $this->getRepositoryMock();
        $repository
            ->expects($this->any())
            ->method('getContentTypeService')
            ->will($this->returnValue($contentTypeServiceMock));

        return $repository;
    }
}
