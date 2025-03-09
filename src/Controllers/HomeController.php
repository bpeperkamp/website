<?php

namespace Controllers;

use Classes\Database;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class HomeController
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index(Request $request, Response $response): Response
    {
        $database = new Database();

        return $this->container
            ->get('view')
            ->render($response, "home/index.html.twig", [
                'articles' => $database->get_articles()
            ]);
    }
}
