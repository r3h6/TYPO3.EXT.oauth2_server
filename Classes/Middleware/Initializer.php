<?php

namespace R3H6\Oauth2Server\Middleware;

use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use R3H6\Oauth2Server\Configuration\Configuration;
use R3H6\Oauth2Server\Domain\Oauth\GrantTypes;
use R3H6\Oauth2Server\ExceptionHandlingTrait;
use R3H6\Oauth2Server\RequestAttributes;
use R3H6\Oauth2Server\Routing\RouterFactory;
use TYPO3\CMS\Core\Authentication\LoginType;
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

class Initializer implements MiddlewareInterface
{
    use ExceptionHandlingTrait;

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

        $typoscript = GeneralUtility::makeInstance(FrontendTypoScript::class, new RootNode(), [], [], []);
        $typoscript->setSetupArray([]);
        $request = $request->withAttribute('frontend.typoscript', $typoscript);
        $GLOBALS['TYPO3_REQUEST'] = $request;

        $this->configuration->merge($this->extensionConfiguration->get('oauth2_server'));
        $this->configuration->merge($siteConfiguration);

        $router = $this->routerFactory->fromRequest($request);
        $route = $router->match($request);

        if ($route === null) {
            return $handler->handle($request);
        }

        $request = $request->withAttribute(RequestAttributes::OAUTH2_ROUTE, $route);

        $post = (array)$request->getParsedBody();
        $grant = GrantTypes::tryFrom($post['grant_type'] ?? '');
        $request = $request->withAttribute(RequestAttributes::OAUTH2_GRANT, $grant);

        if ($grant === GrantTypes::PASSWORD) {
            $post['logintype'] = LoginType::LOGIN;
            $post['user'] = $post['username'] ?? null;
            $post['pass'] = $post['password'] ?? null;
            $request = $request->withParsedBody($post);
            $GLOBALS['TYPO3_CONF_VARS']['SVCONF']['auth']['setup']['FE_fetchUserIfNoSession'] = true;
            $GLOBALS['TYPO3_CONF_VARS']['FE']['checkFeUserPid'] = false;
        }

        if ($request->hasHeader('Authorization')) {
            try {
                $request = $this->handleAuthorization($request);
            } catch (\Exception $exception) {
                return $this->handleException($exception);
            }
        }

        return $handler->handle($request);
    }

    private function handleAuthorization(ServerRequestInterface $request): ServerRequestInterface
    {
        $resourceServer = GeneralUtility::makeInstance(ResourceServer::class);
        $request = $resourceServer->validateAuthenticatedRequest($request);
        $request = $request->withQueryParams(array_merge($request->getQueryParams(), ['logintype' => LoginType::LOGIN]));
        $GLOBALS['TYPO3_CONF_VARS']['SVCONF']['auth']['setup']['FE_fetchUserIfNoSession'] = true;
        $GLOBALS['TYPO3_CONF_VARS']['FE']['checkFeUserPid'] = false;

        return $request;
    }
}
