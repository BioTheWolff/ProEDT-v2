<?php
namespace App\Middlewares;


use App\Database\Managers\AbstractManager;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use League\Plates\Engine;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequiresDatabaseMiddleware implements MiddlewareInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!AbstractManager::is_available($this->container->get(\PDO::class)))
        {
            // there was an error with the database
            if (str_starts_with($request->getUri()->getPath(), $this->container->get("advanced.api_prefix")))
            {
                // we return a JSON response saying the database isn't working
                return new JsonResponse([
                    'status' => $this->container->get("api.status.error.unavailable"),
                    'error' => "The service is unavailable due to impossibility to contact the database."
                ], 503);
            }
            else
            {
                // visual page, we signify that the database isn't working
                return new HtmlResponse($this->container->get(Engine::class)->render('errors/database_error'), 503);
            }
        }
        else
        {
            return $handler->handle($request);
        }
    }
}