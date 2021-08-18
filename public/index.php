<?php

use function App\map_from_routes;

# Setup the path
chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';

# Create and setup the router and the container
$container = (new App\Factories\ContainerFactory)();

$strategy = (new App\Strategies\FancyStrategy())->setContainer($container);
$router   = (new League\Route\Router)->setStrategy($strategy);

# Setup the middlewares
$router->middlewares([
    new App\Middlewares\HttpsMiddleware(),
    new App\Middlewares\MethodDetectorMiddleware(),
    new App\Middlewares\TralingSlashMiddleware()
]);

# Create the routes
map_from_routes($router, $container, "advanced.routes");


# Handle then dispatch the request
$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals();
$response = $router->dispatch($request);
(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);