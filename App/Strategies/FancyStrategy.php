<?php declare(strict_types=1);

namespace App\Strategies;

use Laminas\Diactoros\Response\HtmlResponse;
use League\Plates\Engine;
use League\Route\Http\Exception\{MethodNotAllowedException, NotFoundException};
use League\Route\Route;
use League\Route\Strategy\AbstractStrategy;
use League\Route\{ContainerAwareInterface, ContainerAwareTrait};
use Middlewares\Whoops;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};


/**
 * This strategy aims to provide smooth error handling by displaying an appropriate response to the end user.
 *
 * @package App\Strategies
 * @author Fabien Zoccola
 */
class FancyStrategy extends AbstractStrategy implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function invokeRouteCallable(Route $route, ServerRequestInterface $request): ResponseInterface
    {
        $controller = $route->getCallable($this->getContainer());

        $response = $controller($request, $route->getVars());
        $response = $this->applyDefaultResponseHeaders($response);

        return $response;
    }

    public function getNotFoundDecorator(NotFoundException $exception): MiddlewareInterface
    {
        return $this->returnFancyErrorMiddleware(404, '404 Not Found');
    }

    public function getMethodNotAllowedDecorator(MethodNotAllowedException $exception): MiddlewareInterface
    {
        return $this->returnFancyErrorMiddleware(405, '405 Method Not Allowed');
    }

    /**
     * Return a middleware that simply throws an error
     *
     * @param Int $code
     * @param String $title
     * @return MiddlewareInterface
     */
    protected function returnFancyErrorMiddleware(Int $code, String $title): MiddlewareInterface
    {
        return new class($code, $title, $this->container) implements MiddlewareInterface
        {
            protected $code;
            protected $title;
            protected $container;

            public function __construct(Int $code, String $title, ContainerInterface $container)
            {
                $this->code = $code;
                $this->title = $title;
                $this->container = $container;
            }

            public function process(ServerRequestInterface $request, RequestHandlerInterface $requestHandler) : ResponseInterface {
                $templates = $this->container->get(Engine::class);

                return new HtmlResponse(
                    $templates->render('errors/http_error', ['error_title' => $this->title]),
                    (int)$this->code
                );
            }
        };
    }

    public function getExceptionHandler(): MiddlewareInterface
    {
        return $this->getThrowableHandler();
    }

    public function getThrowableHandler(): MiddlewareInterface
    {
        return new Whoops;
    }
}
