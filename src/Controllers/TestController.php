<?php

namespace Controllers;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class TestController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index(Request $request, Response $response)
    {
        return $this->container
            ->get('view')
            ->render($response, "home/test.html.twig");
    }
}
