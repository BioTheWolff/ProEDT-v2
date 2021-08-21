<?php

use function App\array_resolve;
use function App\map_from_routes;

# Setup the path
chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';

# Create and setup the router and the container
$container = (new App\Factories\ContainerFactory)();

$strategy = (new App\Strategies\FancyStrategy())->setContainer($container);
$router   = (new App\PDTR\PDTRRouter)->setStrategy($strategy);

# Setup the middlewares
$router->middlewares(array_resolve([
    App\Middlewares\HttpsMiddleware::class,
    App\Middlewares\MethodDetectorMiddleware::class,
    App\Middlewares\RequiresDatabaseMiddleware::class,
], $container));

# Create the routes
map_from_routes($router, $container, "advanced.routes");


# Handle then dispatch the request
$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals();
$response = $router->dispatch($request);
(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);