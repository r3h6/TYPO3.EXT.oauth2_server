<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use R3H6\Oauth2Server\ExceptionHandlingTrait;
use R3H6\Oauth2Server\Http\RequestAttribute;
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
 * Oauth2Dispatcher
 */
class Oauth2Dispatcher implements MiddlewareInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
    use ExceptionHandlingTrait;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $request->getAttribute(RequestAttribute::ROUTE);
        if ($route === null) {
            return $handler->handle($request);
        }

        $target = $request->getAttribute(RequestAttribute::TARGET);
        if ($target === true) {
            return $handler->handle($request);
        }

        if ($target === null) {
            return $this->withErrorHandling(function () {
                throw new \RuntimeException('Found route without verified target', 1614020502251);
            });
        }

        [$className, $methodName] = explode('::', $target, 2);
        $controller = GeneralUtility::makeInstance($className);
        $callback = [$controller, $methodName];
        $arguments = [$request];

        return $this->withErrorHandling(function () use ($callback, $arguments) {
            return call_user_func_array($callback, $arguments);
        });
    }
}
