<?php

namespace Controllers;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Database;

class CategoryController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index(Request $request, Response $response)
    {
        $database = new Database();

        return $this->container
            ->get('view')
            ->render($response, "category/index.html.twig", [
                'categories' => $database->get_categories()
            ]);
    }

    public function show(Request $request, Response $response)
    {
        $route = $request->getAttribute('route');
        $slug = $route->getArgument('slug');

        $database = new Database();

        return $this->container
            ->get('view')
            ->render($response, "category/show.html.twig", [
                'articles' => $database->get_articles_by_category($slug)
            ]);
    }
}
