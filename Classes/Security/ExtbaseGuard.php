<?php

namespace R3H6\Oauth2Server\Security;

use Psr\Http\Message\ServerRequestInterface;
use R3H6\Oauth2Server\Exception\AccessDeniedException;
use R3H6\Oauth2Server\ExceptionHandlingTrait;
use R3H6\Oauth2Server\Http\RequestAttribute;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Mvc\Response;

class ExtbaseGuard
{
    use ExceptionHandlingTrait;

    public function checkAccess(ServerRequestInterface $request, string $routeName, Response $response)
    {
        try {
            $target = $request->getAttribute(RequestAttribute::TARGET);
            $route = $request->getAttribute(RequestAttribute::ROUTE);
            if ($target !== true || $route === null || $route->getName() !== $routeName) {
                throw new AccessDeniedException('Requested firewall rule did not apply', 1613599356249);
            }
        } catch (\Exception $exception) {
            $this->fillResponseAndStop($exception, $response);
        }
    }

    private function fillResponseAndStop(\Exception $exception, Response $response)
    {
        $errorResponse = $this->withErrorHandling(function () use ($exception) {
            throw $exception;
        });

        $response->setStatus($errorResponse->getStatusCode(), $errorResponse->getReasonPhrase());
        $response->setContent((string)$errorResponse->getBody());
        throw new StopActionException();
    }
}