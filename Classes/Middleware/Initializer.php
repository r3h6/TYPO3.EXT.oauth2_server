<?php

namespace R3H6\Oauth2Server\Middleware;

use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use R3H6\Oauth2Server\Configuration\Configuration;
use R3H6\Oauth2Server\ExceptionHandlingTrait;
use R3H6\Oauth2Server\Routing\RouterFactory;
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

class Initializer implements MiddlewareInterface
{
    use ExceptionHandlingTrait;

    public function __construct(
        private readonly RouterFactory $routerFactory,
        private readonly Configuration $configuration,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $siteConfiguration = $request->getAttribute('site')->getConfiguration()['oauth2'] ?? false;
        if ($siteConfiguration === false || !($siteConfiguration['enabled'] ?? true)) {
            return $handler->handle($request);
        }

        $this->configuration->merge($siteConfiguration);

        $router = $this->routerFactory->fromRequest($request);
        $route = $router->match($request);

        if ($route === null) {
            return $handler->handle($request);
        }

        $request = $request->withAttribute('oauth2.route', $route);

        if ($request->hasHeader('Authorization')) {
            $resourceServer = GeneralUtility::makeInstance(ResourceServer::class);
            try {
                $request = $resourceServer->validateAuthenticatedRequest($request);
                $request = $request->withQueryParams(array_merge($request->getQueryParams(), ['logintype' => 'login']));
                $GLOBALS['TYPO3_CONF_VARS']['SVCONF']['auth']['setup']['FE_fetchUserIfNoSession'] = true;
                $GLOBALS['TYPO3_CONF_VARS']['FE']['checkFeUserPid'] = false;
            } catch (\Exception $exception) {
                return $this->handleException($exception);
            }
        }

        return $handler->handle($request);
    }
}
