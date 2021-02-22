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
use R3H6\Oauth2Server\Http\Firewall\AuthenticatedRule;
use R3H6\Oauth2Server\Http\Firewall\AuthorizationRule;
use R3H6\Oauth2Server\Http\Firewall\HttpsRule;
use R3H6\Oauth2Server\Http\Firewall\IpRule;
use R3H6\Oauth2Server\Http\Firewall\ScopeRule;
use R3H6\Oauth2Server\Http\Firewall\ScopesRule;
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
 * Oauth2Firewall
 */
class Oauth2Firewall implements MiddlewareInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
    use ExceptionHandlingTrait;

    private $rules = [
        'ip' => IpRule::class,
        'https' => HttpsRule::class,
        'scope' => ScopeRule::class,
        'scopes' => ScopesRule::class,
        'authorization' => AuthorizationRule::class,
        'authenticated' => AuthenticatedRule::class,
    ];

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $request->getAttribute(RequestAttribute::ROUTE);
        if ($route === null) {
            return $handler->handle($request);
        }
        $rules = [];
        $options = array_merge(['authorization' => true], $route->getOptions());
        foreach ($options as $optionName => $optionValue) {
            $className = $this->rules[$optionName] ?? null;
            if ($className !== null) {
                $rules[$optionName] = GeneralUtility::makeInstance($className, $optionValue);
            }
        }

        try {
            foreach ($rules as $rule) {
                $rule($request);
            }
        } catch (\Exception $exception) {
            return $this->withErrorHandling(function () use ($exception) {
                throw $exception;
            });
        }

        $request = $request->withAttribute(RequestAttribute::TARGET, $options['target'] ?? true);

        return $handler->handle($request);
    }
}
