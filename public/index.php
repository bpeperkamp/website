<?php

use Twig\TwigFunction;

use Controllers\HomeController;
use Controllers\ArticleController;
use Controllers\CategoryController;
use Controllers\SearchController;

require realpath(__DIR__ . '/../vendor/autoload.php');

$container = new \Slim\Container();

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('../resources/views', [
        //'cache' => '../storage/cache'
        'cache' => false
    ]);

    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $view;
};

$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container['view']->render($response->withStatus(404), 'generic/404.html.twig', [
            "message" => "Could not find the requested page."
        ]);
    };
};

$function = new TwigFunction('active_route', function () use ($container) {
    $url = $container->get('request')->getUri();
    return strtok($url->getPath(), '/');
});

$container->get('view')->getEnvironment()->addFunction($function);

$app = new \Slim\App($container, [
    "settings" => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false
    ]
]);

$app->get('/', HomeController::class . ':index')->setName('home');
$app->get('/articles', ArticleController::class . ':index')->setName('articles');
$app->get('/article/{slug}', ArticleController::class . ':show')->setName('article');
$app->get('/categories', CategoryController::class . ':index')->setName('categories');
$app->get('/category/{slug}', CategoryController::class . ':show')->setName('category');
$app->post('/search', SearchController::class . ':search')->setName('search');
$app->get('/search', SearchController::class . ':search_submit')->setName('search_submit');

$app->run();
