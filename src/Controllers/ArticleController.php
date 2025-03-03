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

        $categories = $database->get_categories();

        $articles = empty($request->getQueryParam("category")) ? $database->get_articles() : $database->get_articles_by_category($request->getQueryParam("category"));

        return $this->container
            ->get('view')
            ->render($response, "articles/index.html.twig", [
                'articles' => $articles,
                'categories' => $categories,
                'selected_category' => $request->getQueryParam("category")
            ]);
    }

    public function show(Request $request, Response $response)
    {
        $route = $request->getAttribute('route');
        $slug = $route->getArgument('slug');

        $database = new Database();
        $articles = $database->get_article_by_slug($slug);

        if (empty($articles)) {
            return $this->container
                ->get('view')
                ->render($response->withStatus(404), "generic/404.html.twig");
        }

        return $this->container
            ->get('view')
            ->render($response, "articles/show.html.twig", [
                'article' => $articles
            ]);
    }
}
