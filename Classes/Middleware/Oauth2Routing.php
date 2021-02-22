<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use R3H6\Oauth2Server\Http\RequestAttribute;
use R3H6\Oauth2Server\Routing\RouteCollection;
use R3H6\Oauth2Server\Utility\HashUtility;

/***
 *
 * This file is part of the "OAuth2 Server" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020
 *
 ***/

/**
 * Oauth2Routing
 */
class Oauth2Routing implements MiddlewareInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $configuration = $request->getAttribute(RequestAttribute::CONFIGURATION);
        if ($configuration === null) {
            return $handler->handle($request);
        }

        $method = $request->getParsedBody()['_method'] ?? null;
        if ($method !== null) {
            $request = $request->withMethod($method);
        }

        $query = $request->getQueryParams();
        if (isset($query['_'])) {
            $query['redirect_url'] = HashUtility::decode($query['_']);
            unset($query['_']);
            $request = $request->withQueryParams($query);
        }

        $path = trim($request->getUri()->getPath(), '/');
        $prefix = trim($configuration->getRoutePrefix(), '/');

        $routes = (strpos($path, $prefix . '/') === 0) ?
            RouteCollection::fromArray($configuration->getEndpoints(), $prefix):
            RouteCollection::fromArray($configuration->getResources());

        $route = $routes->match($request);
        $request = $request->withAttribute(RequestAttribute::ROUTE, $route);
        return $handler->handle($request);
    }
}
