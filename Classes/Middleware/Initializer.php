<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Middleware;

use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use R3H6\Oauth2Server\Configuration\Configuration;
use R3H6\Oauth2Server\Domain\Oauth\GrantTypes;
use R3H6\Oauth2Server\ExceptionHandlingTrait;
use R3H6\Oauth2Server\RequestAttributes;
use R3H6\Oauth2Server\Routing\RouterFactory;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\TypoScript\AST\Node\RootNode;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
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

class Initializer implements MiddlewareInterface, LoggerAwareInterface
{
    use ExceptionHandlingTrait;
    use LoggerAwareTrait;

    public function __construct(
        private readonly RouterFactory $routerFactory,
        private readonly Configuration $configuration,
        private readonly ExtensionConfiguration $extensionConfiguration,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $siteConfiguration = $request->getAttribute('site')?->getConfiguration()['oauth2'] ?? false;

        if ($siteConfiguration === false || !($siteConfiguration['enabled'] ?? true)) {
            return $handler->handle($request);
        }

        $this->configuration->merge($this->extensionConfiguration->get('oauth2_server'));
        $this->configuration->merge($siteConfiguration);

        $this->logger->debug('Configure oauth2 server', $this->configuration->toArray());

        $router = $this->routerFactory->fromRequest($request);
        $route = $router->match($request);

        if ($route === null) {
            return $handler->handle($request);
        }

        $request = $this->prepareExtbase($request);
        $request = $request->withAttribute(RequestAttributes::OAUTH2_ROUTE, $route);

        $validateAuthenticatedRequest = $route->getOptions()['oauth2_validateAuthenticatedRequest'] ?? true;
        if ($validateAuthenticatedRequest === false) {
            $request = $this->processAuthorizationRequest($request);
            return $handler->handle($request);
        }

        try {
            $request = $this->processResourceRequest($request);
        } catch (\Exception $exception) {
            return $this->handleException($exception);
        }

        return $handler->handle($request);
    }

    private function prepareExtbase(ServerRequestInterface $request): ServerRequestInterface
    {
        $typoscript = GeneralUtility::makeInstance(FrontendTypoScript::class, new RootNode(), [], [], []);
        $typoscript->setSetupArray([]);
        $request = $request->withAttribute('frontend.typoscript', $typoscript);
        $GLOBALS['TYPO3_REQUEST'] = $request;
        return $request;
    }

    private function processAuthorizationRequest(ServerRequestInterface $request): ServerRequestInterface
    {
        $this->logger->debug('Process authorization request');

        $post = (array)$request->getParsedBody();
        $grant = GrantTypes::tryFrom($post['grant_type'] ?? '');
        $request = $request->withAttribute(RequestAttributes::OAUTH2_GRANT, $grant);

        if ($grant === GrantTypes::PASSWORD) {
            $this->logger->debug('Enhance request with required login parameters');
            $post['logintype'] = 'login';
            $post['user'] = $post['username'] ?? null;
            $post['pass'] = $post['password'] ?? null;
            $request = $request->withParsedBody($post);
            $this->updateGlobalConfiguration();
        }

        return $request;
    }

    private function processResourceRequest(ServerRequestInterface $request): ServerRequestInterface
    {
        $this->logger->debug('Process resource request');
        $resourceServer = GeneralUtility::makeInstance(ResourceServer::class);
        $request = $resourceServer->validateAuthenticatedRequest($request);
        $this->logger->debug('Enhance request with required login parameters');
        $request = $request->withQueryParams(array_merge($request->getQueryParams(), ['logintype' => 'login']));
        $this->updateGlobalConfiguration();

        return $request;
    }

    private function updateGlobalConfiguration(): void
    {
        $this->logger->debug('Update global configuration to enforce login');
        $GLOBALS['TYPO3_CONF_VARS']['SVCONF']['auth']['setup']['FE_fetchUserIfNoSession'] = true;
        $GLOBALS['TYPO3_CONF_VARS']['FE']['checkFeUserPid'] = false;
        $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'][] = 'logintype';
    }
}
