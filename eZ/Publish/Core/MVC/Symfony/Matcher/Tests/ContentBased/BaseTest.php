<?php

/**
 * File containing the BaseTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\MVC\Symfony\Matcher\Tests\ContentBased;

use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\Core\Repository\Values\Content\VersionInfo;
use PHPUnit_Framework_TestCase;

abstract class BaseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $repositoryMock;

    protected function setUp()
    {
        parent::setUp();
        $this->repositoryMock = $this->getRepositoryMock();
    }

    /**
     * @param array $matchingConfig
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getPartiallyMockedViewProvider(array $matchingConfig = array())
    {
        return $this
            ->getMockBuilder('eZ\\Publish\\Core\\MVC\\Symfony\\View\\Provider\\Location\\Configured')
            ->setConstructorArgs(
                array(
                    $this->repositoryMock,
                    $matchingConfig,
                )
            )
            ->setMethods(array('getMatcher'))
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getRepositoryMock()
    {
        $repositoryClass = 'eZ\\Publish\\Core\\Repository\\Repository';

        return $this
            ->getMockBuilder($repositoryClass)
            ->disableOriginalConstructor()
            ->setMethods(
                array_diff(
                    get_class_methods($repositoryClass),
                    array('sudo')
                )
            )
            ->getMock();
    }

    /**
     * @param array $properties
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getLocationMock(array $properties = array())
    {
        return $this
            ->getMockBuilder('eZ\\Publish\\API\\Repository\\Values\\Content\\Location')
            ->setConstructorArgs(array($properties))
            ->getMockForAbstractClass();
    }

    /**
     * @param \eZ\Publish\API\Repository\Values\Content\Location $location
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getLocationValueViewMock(Location $location)
    {
        $mock = $this
            ->getMock('eZ\\Publish\\Core\\MVC\\Symfony\\Matcher\\Tests\\Stubs\\LocationValueView');

        $mock
            ->expects($this->any())
            ->method('getLocation')
            ->will($this->returnValue($location));

        return $mock;
    }

    /**
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getContentValueViewMock(Content $content)
    {
        $mock = $this
            ->getMock('eZ\\Publish\\Core\\MVC\\Symfony\\Matcher\\Tests\\Stubs\\ContentValueView');

        $mock
            ->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue($content));

        return $mock;
    }

    /**
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getContentViewMock(Content $content)
    {
        $mock = $this
            ->getMock('eZ\\Publish\\Core\\MVC\\Symfony\\View\\ContentView');

        $mock
            ->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue($content));

        return $mock;
    }

    /**
     * @param array $properties
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getContentInfoMock(array $properties = array())
    {
        return $this->
            getMockBuilder('eZ\\Publish\\API\\Repository\\Values\\Content\\ContentInfo')
            ->setConstructorArgs(array($properties))
            ->getMockForAbstractClass();
    }

    /**
     * @param ContentInfo $contentInfo
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getContentMock(ContentInfo $contentInfo)
    {
        return $this->
            getMockBuilder('eZ\\Publish\\Core\\Repository\\Values\\Content\\Content')
            ->setConstructorArgs(array(array('versionInfo' => new VersionInfo(array('contentInfo' => $contentInfo)))))
            ->getMockForAbstractClass();
    }
}
