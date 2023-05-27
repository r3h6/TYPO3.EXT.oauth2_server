<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use R3H6\Oauth2Server\Configuration\Configuration;
use R3H6\Oauth2Server\ExceptionHandlingTrait;
use R3H6\Oauth2Server\Http\RequestAttribute;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
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
 * Oauth2Configuration
 */
class Oauth2Configuration implements MiddlewareInterface
{
    use ExceptionHandlingTrait;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $siteConfiguration = $request->getAttribute('site')->getConfiguration()['oauth2'] ?? false;
        if ($siteConfiguration === false || !($siteConfiguration['enabled'] ?? true)) {
            return $handler->handle($request);
        }

        $configuration = GeneralUtility::makeInstance(Configuration::class);

        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $globalConfiguration = $extensionConfiguration->get('oauth2_server');

        $configuration->merge(array_filter($globalConfiguration));
        $configuration->merge($siteConfiguration);

        $request = $request->withAttribute(RequestAttribute::CONFIGURATION, $configuration);
        return $handler->handle($request);
    }
}
