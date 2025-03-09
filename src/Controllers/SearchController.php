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

            $csrf_token = $request->getHeader("X-CSRF-TOKEN");

            if (!hash_equals($_SESSION["csrf_token"], $csrf_token[0])) {
                return $response->withStatus(403);
            }

            $sanitized_query = htmlspecialchars($query["data"], ENT_QUOTES, 'UTF-8');

            $articles = $database->search_articles($sanitized_query);

            $response_data = [
                "success" => false,
                "xhr" => null,
                "query" => null,
                "result" => null,
                "csrf" => $csrf_token[0]
            ];

            if (isset($query["data"])) {
                $response_data["success"] = true;
                $response_data["xhr"] = $request->isXhr();
                $response_data["query"] = $query["data"];
                $response_data["result"] = $articles;
            }

            return $response->withJson($response_data, 200);
        }

        $csrf_form_token = htmlspecialchars($query["csrf_token"], ENT_QUOTES, 'UTF-8');

        if (!hash_equals($_SESSION["csrf_token"], $csrf_form_token)) {
            return $response->withRedirect($this->container->get("router")->pathFor("home"));
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
