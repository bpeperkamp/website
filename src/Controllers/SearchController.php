<?php

namespace Controllers;

use Classes\Database;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class SearchController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function search(Request $request, Response $response)
    {
        var_dump("testes");
    }
}
