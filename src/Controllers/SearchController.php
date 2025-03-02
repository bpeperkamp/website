<?php

namespace Controllers;

use Classes\Database;
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

    public function search(Request $request, Response $response): ResponseInterface
    {
        $database = new Database();

        if ($request->isXhr()) {
            $response_data = [
                "success" => false,
                "xhr" => null,
                "query" => null,
                "result" => null
            ];

            $query = $request->getParsedBody();
            $sanitized_input = htmlspecialchars($query["data"], ENT_QUOTES, 'UTF-8');
            $articles = $database->search_articles($sanitized_input);

            if (isset($query["data"])) {
                $response_data["success"] = true;
                $response_data["xhr"] = $request->isXhr();
                $response_data["query"] = $query["data"];
                $response_data["result"] = $articles;
            }

            return $response->withJson($response_data, 200);
        }

        return $this->container
            ->get('view')
            ->render($response, "search/index.html.twig", [
                'articles' => []
            ]);
    }
}
