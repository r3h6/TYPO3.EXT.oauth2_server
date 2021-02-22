<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Routing;

use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
 * RouteCollection
 */
class RouteCollection implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private $routes = [];

    public static function fromArray(array $array, string $prefix = ''): self
    {
        $routes = GeneralUtility::makeInstance(__CLASS__);
        foreach ($array as $routeName => $routeConfiguration) {
            $path = $routeConfiguration['path'];
            if ($prefix !== '') {
                $path = rtrim($prefix, '/') . '/' . ltrim($path, '/');
            }

            $route = new Route($routeName, $path);

            $methods = $routeConfiguration['methods'] ?? [];
            $route->setMethods(
                is_array($methods) ? $methods : GeneralUtility::trimExplode('|', $methods, true)
            );

            $route->setOptions($routeConfiguration);
            $routes->add($route);
        }
        return $routes;
    }

    public function add(Route $route)
    {
        $this->routes[$route->getName()] = $route;
    }

    public function match(RequestInterface $request): ?Route
    {
        $requestPath = trim($request->getUri()->getPath(), '/');
        $requestMethod = $request->getMethod();

        foreach ($this->routes as $routeName => $route) {
            $routePath = '/^' . addcslashes(trim($route->getPath(), '/^$'), '/') . '$/i';
            if (!preg_match($routePath, $requestPath)) {
                $this->logger->debug('path did not match pattern', ['path' => $requestPath, 'rule' => $routePath]);
                continue; // No match, try next rule
            }

            $routeMethods = $route->getMethods();
            if (!empty($routeMethods) && !in_array($requestMethod, $routeMethods)) {
                $this->logger->debug('method did not match pattern', ['method' => $requestMethod, 'rule' => $routeMethods]);
                continue; // No match, try next rule
            }

            $this->logger->debug('matched route ', ['key' => $routeName]);

            return $route;
        }
        return null;
    }
}
