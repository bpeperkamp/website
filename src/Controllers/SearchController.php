<?php

namespace Controllers;

use Classes\Database;
use Classes\Logger;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Psr\Http\Message\ResponseInterface;

class SearchController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function search(Request $request, Response $response)
    {
        $database = new Database();

        $query = $request->getParsedBody();

        if ($request->isXhr()) {
            $sanitized_input = htmlspecialchars($query["data"], ENT_QUOTES, 'UTF-8');
            $articles = $database->search_articles($sanitized_input);

            $response_data = [
                "success" => false,
                "xhr" => null,
                "query" => null,
                "result" => null
            ];

            if (isset($query["data"])) {
                $response_data["success"] = true;
                $response_data["xhr"] = $request->isXhr();
                $response_data["query"] = $query["data"];
                $response_data["result"] = $articles;
            }

            return $response->withJson($response_data, 200);
        }

        return $response->withRedirect($this->container->get("router")->pathFor("search_submit", [], ["query" => $query["search"]]), 301);
    }

    public function search_submit(Request $request, Response $response)
    {
        $query = $request->getQueryParams();
        $sanitized_input = htmlspecialchars($query["query"], ENT_QUOTES, 'UTF-8');

        $database = new Database();
        $articles = $database->search_articles($sanitized_input);

        return $this->container
            ->get('view')
            ->render($response, "search/index.html.twig", [
                'articles' => $articles
            ]);
    }
}
