<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Security;

use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;
use R3H6\Oauth2Server\ExceptionHandlingTrait;
use R3H6\Oauth2Server\Http\RequestAttribute;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;

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
 * ExtbaseGuard
 */
class ExtbaseGuard
{
    use ExceptionHandlingTrait;

    public function checkAccess(ServerRequestInterface $request, string $routeName, $response = null)
    {
        try {
            $target = $request->getAttribute(RequestAttribute::TARGET);
            $route = $request->getAttribute(RequestAttribute::ROUTE);
            if ($target !== true || $route === null || $route->getName() !== $routeName) {
                throw OAuthServerException::accessDenied('Requested firewall rule did not apply');
            }
        } catch (\Exception $exception) {
            if (version_compare(TYPO3_version, '11.5', '>=')) {
                $errorResponse = $this->withErrorHandling(function () use ($exception) {
                    throw $exception;
                });
                // @phpstan-ignore-next-line
                throw new StopActionException('', 0, null, $errorResponse);
            }
            $this->fillResponseAndStop($exception, $response);
        }
    }

    private function fillResponseAndStop(\Exception $exception, $response)
    {
        $errorResponse = $this->withErrorHandling(function () use ($exception) {
            throw $exception;
        });

        $response->setStatus($errorResponse->getStatusCode(), $errorResponse->getReasonPhrase());
        $response->setContent((string)$errorResponse->getBody());
        throw new StopActionException();
    }
}
