<?php

namespace eZ\Publish\Core\MVC\Symfony\Matcher\Tests\Stubs;

use eZ\Publish\Core\MVC\Symfony\View\View;
use eZ\Publish\Core\MVC\Symfony\View\LocationValueView as LocationValueViewInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerReference;

class LocationValueView implements View, LocationValueViewInterface
{
    public function setTemplateIdentifier( $templateIdentifier )
    {
    }

    public function getTemplateIdentifier()
    {
    }

    public function setParameters( array $parameters )
    {
    }

    public function addParameters( array $parameters )
    {
    }

    public function getParameters()
    {
    }

    public function hasParameter( $parameterName )
    {
    }

    public function getParameter( $parameterName )
    {
    }

    public function setConfigHash( array $config )
    {
    }

    public function getConfigHash()
    {
    }

    public function setViewType( $viewType )
    {
    }

    public function getViewType()
    {
    }

    public function setControllerReference( ControllerReference $controllerReference )
    {
    }

    public function getControllerReference()
    {
    }

    public function setResponse( Response $response )
    {
    }

    public function getResponse()
    {
    }

    public function getLocation()
    {
    }
}
