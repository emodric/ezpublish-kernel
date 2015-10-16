<?php

/**
 * File containing the CacheViewResponseListenerTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Bundle\EzPublishCoreBundle\Tests\EventListener;

use eZ\Bundle\EzPublishCoreBundle\EventListener\CacheViewResponseListener;
use eZ\Bundle\EzPublishCoreBundle\Tests\EventListener\Stubs\UncachableView;
use eZ\Publish\Core\MVC\Symfony\View\ContentView;
use eZ\Publish\Core\Repository\Values\Content\Location;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use PHPUnit_Framework_TestCase;

class CacheViewResponseListenerTest extends PHPUnit_Framework_TestCase
{
    public function testGetSubscribedEvents()
    {
        $this->assertSame(
            [KernelEvents::RESPONSE => 'configureCache'],
            CacheViewResponseListener::getSubscribedEvents()
        );
    }

    public function testConfigureCacheWithNoView()
    {
        $request = Request::create('/foo');

        $response = new Response();

        $listener = new CacheViewResponseListener(true, true, 600);
        $listener->configureCache($this->getEvent($request, $response));

        $this->assertSame(false, $response->isCacheable());
        $this->assertSame(null, $response->getMaxAge());
        $this->assertSame(false, $response->headers->has('X-Location-Id'));
    }

    public function testConfigureCacheWithUncachableView()
    {
        $request = Request::create('/foo');
        $request->attributes->set('view', new UncachableView());

        $response = new Response();

        $listener = new CacheViewResponseListener(true, true, 600);
        $listener->configureCache($this->getEvent($request, $response));

        $this->assertSame(false, $response->isCacheable());
        $this->assertSame(null, $response->getMaxAge());
        $this->assertSame(false, $response->headers->has('X-Location-Id'));
    }

    public function testConfigureCacheWithDisabledCache()
    {
        $request = Request::create('/foo');

        $view = new ContentView();
        $view->setMaxAge(42);
        $view->setSharedMaxAge(24);

        $request->attributes->set('view', $view);

        $response = new Response();

        $listener = new CacheViewResponseListener(false, true, 600);
        $listener->configureCache($this->getEvent($request, $response));

        $this->assertSame(false, $response->isCacheable());
        $this->assertSame(null, $response->getMaxAge());
        $this->assertSame(false, $response->headers->has('X-Location-Id'));
    }

    public function testConfigureCacheWithDisabledCacheOnView()
    {
        $request = Request::create('/foo');

        $view = new ContentView();
        $view->setCacheEnabled(false);
        $view->setMaxAge(42);
        $view->setSharedMaxAge(24);

        $request->attributes->set('view', $view);

        $response = new Response();

        $listener = new CacheViewResponseListener(true, true, 600);
        $listener->configureCache($this->getEvent($request, $response));

        $this->assertSame(false, $response->isCacheable());
        $this->assertSame(null, $response->getMaxAge());
        $this->assertSame(false, $response->headers->has('X-Location-Id'));
    }

    public function testConfigureCacheWithNoLocation()
    {
        $request = Request::create('/foo');

        $view = new ContentView();
        $request->attributes->set('view', $view);

        $response = new Response();

        $listener = new CacheViewResponseListener(true, true, 600);
        $listener->configureCache($this->getEvent($request, $response));

        $this->assertSame(false, $response->headers->has('X-Location-Id'));
    }

    public function testConfigureCacheWithNoLocationAndXLocationIdHeaderSet()
    {
        $request = Request::create('/foo');

        $view = new ContentView();
        $request->attributes->set('view', $view);

        $response = new Response();
        $response->headers->set('X-Location-Id', 42);

        $listener = new CacheViewResponseListener(true, true, 600);
        $listener->configureCache($this->getEvent($request, $response));

        $this->assertSame(42, $response->headers->get('X-Location-Id'));
    }

    public function testConfigureCacheWithLocation()
    {
        $request = Request::create('/foo');

        $view = new ContentView();
        $view->setLocation(new Location(array('id' => 42)));
        $request->attributes->set('view', $view);

        $response = new Response();

        $listener = new CacheViewResponseListener(true, true, 600);
        $listener->configureCache($this->getEvent($request, $response));

        $this->assertSame(42, $response->headers->get('X-Location-Id'));
    }

    public function testConfigureCacheWithLocationAndXLocationIdHeaderSet()
    {
        $request = Request::create('/foo');

        $view = new ContentView();
        $view->setLocation(new Location(array('id' => 42)));
        $request->attributes->set('view', $view);

        $response = new Response();
        $response->headers->set('X-Location-Id', 24);

        $listener = new CacheViewResponseListener(true, true, 600);
        $listener->configureCache($this->getEvent($request, $response));

        $this->assertSame(24, $response->headers->get('X-Location-Id'));
    }

    public function testConfigureCacheWithDisabedTtlCache()
    {
        $request = Request::create('/foo');

        $view = new ContentView();
        $view->setMaxAge(42);
        $view->setSharedMaxAge(24);
        $request->attributes->set('view', $view);

        $response = new Response();

        $listener = new CacheViewResponseListener(true, false, 600);
        $listener->configureCache($this->getEvent($request, $response));

        $this->assertSame(null, $response->getMaxAge());
    }

    public function testConfigureCacheWithMaxAge()
    {
        $request = Request::create('/foo');

        $view = new ContentView();
        $view->setMaxAge(42);
        $request->attributes->set('view', $view);

        $response = new Response();

        $listener = new CacheViewResponseListener(true, true, 600);

        $listener->configureCache($this->getEvent($request, $response));

        $this->assertSame(42, (int)$response->headers->getCacheControlDirective('max-age'));
    }

    public function testConfigureCacheWithSharedMaxAge()
    {
        $request = Request::create('/foo');

        $view = new ContentView();
        $view->setSharedMaxAge(42);
        $request->attributes->set('view', $view);

        $response = new Response();

        $listener = new CacheViewResponseListener(true, true, 600);
        $listener->configureCache($this->getEvent($request, $response));

        $this->assertSame(42, (int)$response->headers->getCacheControlDirective('s-maxage'));
    }

    public function testConfigureCacheWithDefaultTtl()
    {
        $request = Request::create('/foo');

        $request->attributes->set('view', new ContentView());

        $response = new Response();

        $listener = new CacheViewResponseListener(true, true, 600);
        $listener->configureCache($this->getEvent($request, $response));

        $this->assertSame(600, (int)$response->headers->getCacheControlDirective('s-maxage'));
    }

    protected function getEvent(Request $request, Response $response)
    {
        return new FilterResponseEvent(
            $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface'),
            $request,
            HttpKernelInterface::MASTER_REQUEST,
            $response
        );
    }
}
