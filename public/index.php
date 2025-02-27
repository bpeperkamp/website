<?php

use Controllers\HomeController;
use Controllers\TestController;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require realpath(__DIR__ . '/../vendor/autoload.php');

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$container = new \Slim\Container();
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('../resources/views', [
        //'cache' => 'path/to/cache'
        'cache' => false
    ]);

    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $view;
};

$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container['view']->render($response->withStatus(404), 'generic/404.php', [
            "message" => "Could not find the requested page."
        ]);
    };
};

$app = new \Slim\App($container, ["settings" => $config]);

$app->get('/', HomeController::class . ':index');
$app->get('/test', TestController::class . ':index');

$app->run();
