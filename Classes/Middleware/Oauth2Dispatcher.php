<?php

namespace R3H6\Oauth2Server\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use R3H6\Oauth2Server\Domain\Resource;
use R3H6\Oauth2Server\ExceptionHandlingTrait;
use R3H6\Oauth2Server\Http\Oauth2ServerInterface;
use R3H6\Oauth2Server\Http\RequestAttribute;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Oauth2Dispatcher implements MiddlewareInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
    use ExceptionHandlingTrait;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $target = $request->getAttribute(RequestAttribute::TARGET);
        if ($target === null || $target === true) {
            return $handler->handle($request);
        }

        [$className, $methodName] = explode('::', $target, 2);
        $controller = GeneralUtility::makeInstance($className);
        $callback = [$controller, $methodName];
        $arguments = [$request];

        return $this->withErrorHandling(function () use ($callback, $arguments) {
            return call_user_func_array($callback, $arguments);
        });
    }

    // public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    // {
    //     $configuration = $request->getAttribute(Oauth2Configuration::REQUEST_ATTRIBUTE_NAME);
    //     if ($configuration === null) {
    //         return $handler->handle($request);
    //     }

    //     $path = trim($request->getUri()->getPath(), '/');
    //     $prefix = trim($configuration->getRoutePrefix(), '/');
    //     if (strpos($path, $prefix . '/') === 0) {
    //         $serverClass = $configuration->getServerClass();
    //         $server = GeneralUtility::makeInstance($serverClass);
    //         if (!($server instanceof Oauth2ServerInterface)) {
    //             throw new \RuntimeException('Server must implement "'.Oauth2ServerInterface::class.'"', 1613338040226);
    //         }
    //         return $this->withErrorHandling(function () use ($server, $request) {
    //             return $server->handleRequest($request);
    //         });
    //     }

    //     $resource = $request->getAttribute(Resource::REQUEST_ATTRIBUTE_NAME);
    //     if ($resource instanceof Resource && $resource->getTarget() !== null) {
    //         $request = $request->withAttribute('target', $resource->getTarget());
    //         return $this->dispatcher->dispatch($request);
    //     }

    //     return $handler->handle($request);
    // }
}
