<?php
namespace App;

use League\Route\Router;
use Psr\Container\ContainerInterface;
use UnexpectedValueException;

/**
 * Resolves an array of classes through the container
 * @param string[] $classes The classes to resolve
 * @param ContainerInterface $container The container
 * @return object[]
 * @author Vasco Compain
 */
function array_resolve(array $classes, ContainerInterface $container): array
{
    //Filter non classes out
    $classes = array_filter($classes, function ($className) {
        return !is_string($className) OR class_exists($className);
    });

    //Instantiate classes
    return array_map(function (string $className) use ($container) {
        return $container->get($className);
    }, $classes);
}

function e(String $s) {
    return htmlspecialchars($s);
}

/**
 * Reads the routes from the container, and adds the interpreted routes to the given router
 * @param Router $router the router to add the routes to
 * @param ContainerInterface $container the container we get the endpoints from
 * @param string $routes_path the paths to get the routes from using the container
 * @author Fabien Zoccola
 */
function map_from_routes(Router $router, ContainerInterface $container, string $routes_path) {
    $prefix = 'path';
    $calculated_routes = [];
    $array = $container->get($routes_path);

    // list all the groups
    foreach ($array as $group_name => $group)
    {
        if ($group_name == "index")
        {
            $calculated_routes[] = ["GET", "path.index", $group];
            continue;
        }

        // grab the controller
        $controller = $group['controller'];

        // loop over all the possible routes of that group
        foreach ($group['routes'] as $route => $value)
        {
            if (is_array($value))
            {
                // select each item of the array, following [HTTP_METHOD => PHP_METHOD_TO_CALL]
                foreach ($value as $http_method => $function_to_call) {
                    $calculated_routes[] = [$http_method, "$prefix.$group_name.$route", [$controller, $function_to_call]];
                }
            }
            else if (is_string($value))
            {
                $calculated_routes[] = ["GET", "$prefix.$group_name.$route", [$controller, $value]];
            }
            else {
                throw new UnexpectedValueException(
                    "Unexpected value type, waiting for array or string, got " . gettype($value) . " on $prefix.$group.$route"
                );
            }
        }
    }

    // then add all the calculated routes to the router
    foreach ($calculated_routes as $r) {
        $endpoint = $container->get($r[1]);

        if (is_array($endpoint))
        {
            foreach ($endpoint as $e)
            {
                $router->map($r[0], $e, $r[2]);
            }
        }
        else
        {
            $router->map($r[0], $endpoint, $r[2]);
        }

    }
}