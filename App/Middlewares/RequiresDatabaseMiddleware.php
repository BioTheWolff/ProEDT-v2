<?php
namespace App\Middlewares;


use App\Database\Managers\AbstractManager;
use Laminas\Diactoros\Response\HtmlResponse;
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
            return new HtmlResponse($this->container->get(Engine::class)->render('errors/database_error'));
        }
        else
        {
            return $handler->handle($request);
        }
    }
}