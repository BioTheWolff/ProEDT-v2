<?php
namespace App\Middlewares;

use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware that forces the request to be without a trailing slash (to avoid confusion with folders)
 *
 * @package App\Middlewares
 * @author Fabien Zoccola, Vasco Compain
 */
class TralingSlashMiddleware implements MiddlewareInterface {

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri()->getPath();
        if ($uri !== '/' AND !empty($uri) AND $uri[-1] === '/') {
            return new RedirectResponse(substr($uri, 0, -1), 301);
        }
        return $handler->handle($request);
    }
}
