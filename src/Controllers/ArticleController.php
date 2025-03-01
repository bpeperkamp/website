<?php

namespace Controllers;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Database;

class ArticleController
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
            ->render($response, "articles/index.html.twig", [
                'articles' => $database->get_articles()
            ]);
    }

    public function show(Request $request, Response $response)
    {
        $route = $request->getAttribute('route');
        $slug = $route->getArgument('slug');

        $database = new Database();

        return $this->container
            ->get('view')
            ->render($response, "articles/show.html.twig", [
                'article' => $database->get_article_by_slug($slug)
            ]);
    }
}
