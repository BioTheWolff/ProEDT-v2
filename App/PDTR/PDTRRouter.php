<?php

namespace App\PDTR;

use Laminas\Diactoros\Response\RedirectResponse;
use League\Route\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * PDTR stands for Pre-Dispatch Trailing (slash) Redirect.
 *
 * This customised router fixes the FastRoute system used by League/Route to determine whether to return 404 or not
 * BEFORE any middlewares can be used, rendering the TrailingSlashMiddleware useless
 *
 * This class aims to fix this behaviour
 *
 * @package App\PDTR
 */
class PDTRRouter extends Router
{
    public function dispatch(ServerRequestInterface $request): ResponseInterface
    {
        $uri = $request->getUri()->getPath();
        if ($uri !== '/' AND !empty($uri) AND $uri[-1] === '/') {
            return new RedirectResponse(substr($uri, 0, -1), 301);
        }

        return parent::dispatch($request);
    }
}