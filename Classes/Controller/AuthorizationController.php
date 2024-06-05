<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Controller;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use R3H6\Oauth2Server\Configuration\Configuration;
use R3H6\Oauth2Server\Domain\Model\Client;
use R3H6\Oauth2Server\Domain\Repository\AccessTokenRepository;
use R3H6\Oauth2Server\Domain\Repository\UserRepository;
use R3H6\Oauth2Server\Mvc\Controller\AuthorizationContext;
use R3H6\Oauth2Server\Session\Session;
use R3H6\Oauth2Server\Utility\ScopeUtility;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Routing\RouterInterface;

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

class AuthorizationController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly AccessTokenRepository $accessTokenRepository,
        private readonly AuthorizationServer $server,
        private readonly LoggerInterface $logger,
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly Configuration $configuration
    ) {}

    public function startAuthorization(ServerRequestInterface $request): ResponseInterface
    {
        $this->logger->debug('Start authorization');
        $authRequest = $this->server->validateAuthorizationRequest($request);
        $context = new AuthorizationContext($request, $authRequest, $this->configuration);

        if ($context->isAuthenticated()) {
            $this->setUserToAuthorizationRequest($context);
        }

        Session::fromRequest($request)->set($authRequest);

        if ($this->requiresAuthentication($context)) {
            return $this->createAuthenticationRedirect($context);
        }

        if ($this->requiresConsent($context)) {
            return $this->createConsentRedirect($context);
        }

        return $this->approveAuthorization($request);
    }

    public function approveAuthorization(ServerRequestInterface $request): ResponseInterface
    {
        $authRequest = Session::fromRequest($request)->get();
        Session::fromRequest($request)->clear();
        if (!$authRequest instanceof AuthorizationRequest) {
            throw new \RuntimeException('Try to approve authorization without starting it', 1614192910231);
        }

        $context = new AuthorizationContext($request, $authRequest, $this->configuration);
        $this->logger->debug('Approve authorization');
        return $this->finishAuthorization($context, true);
    }

    public function denyAuthorization(ServerRequestInterface $request): ResponseInterface
    {
        $authRequest = Session::fromRequest($request)->get();
        Session::fromRequest($request)->clear();
        if (!$authRequest instanceof AuthorizationRequest) {
            throw new \RuntimeException('Try to deny authorization without starting it', 1614192910231);
        }
        $context = new AuthorizationContext($request, $authRequest, $this->configuration);
        $this->logger->debug('Deny authorization');
        return $this->finishAuthorization($context, false);
    }

    protected function finishAuthorization(AuthorizationContext $context, bool $approved): ResponseInterface
    {
        $authRequest = $context->getAuthRequest();
        if ($authRequest->getUser() === null) {
            throw new \RuntimeException('Try to complete authorization request without user', 1614192781931);
        }

        $authRequest->setAuthorizationApproved($approved);

        return $this->server->completeAuthorizationRequest($authRequest, $this->responseFactory->createResponse());
    }

    protected function setUserToAuthorizationRequest(AuthorizationContext $context): void
    {
        $user = $this->userRepository->findByUid($context->getFrontendUserUid());
        $this->logger->debug('Set user to authorization request', ['user' => $user]);
        $context->getAuthRequest()->setUser($user);
    }

    protected function requiresAuthentication(AuthorizationContext $context): bool
    {
        $authRequest = $context->getAuthRequest();
        return $authRequest->getUser() === null;
    }

    protected function requiresConsent(AuthorizationContext $context): bool
    {
        $authRequest = $context->getAuthRequest();
        $user = $authRequest->getUser();
        $client = $authRequest->getClient();
        $scopes = ScopeUtility::toStrings(...$authRequest->getScopes());

        if (empty($scopes)) {
            $this->logger->debug('Does not require consent because of empty scopes');
            return false;
        }

        if ($user && $this->accessTokenRepository->hasValidAccessToken($user->getIdentifier(), $client->getIdentifier(), $scopes)) {
            $this->logger->debug('Does not require consent because of valid access token');
            return false;
        }

        if ($client instanceof Client && $client->doSkipConsent()) {
            $this->logger->debug('Does not requrie consent because of client setting');
            return false;
        }
        return $authRequest->isAuthorizationApproved() !== true;
    }

    protected function createAuthenticationRedirect(AuthorizationContext $context): ResponseInterface
    {
        $selfUrl = (string)$context->getRequest()->getUri();
        $parameters = ['redirect_url' => $selfUrl];
        $forwardUrl = '/?' . http_build_query($parameters);
        $loginPageUid = $context->getConfiguration()->getLoginPageUid();
        if ($loginPageUid) {
            $forwardUrl = (string)$context->getSite()->getRouter()->generateUri((string)$loginPageUid, $parameters, '', RouterInterface::ABSOLUTE_URL);
        }

        $this->logger->debug('Forward to login', ['url' => $forwardUrl]);
        return new RedirectResponse($forwardUrl);
    }

    protected function createConsentRedirect(AuthorizationContext $context): ResponseInterface
    {
        $consentPageUid = $context->getConfiguration()->getConsentPageUid();
        $forwardUrl = (string)$context->getSite()->getRouter()->generateUri((string)$consentPageUid, [], '', RouterInterface::ABSOLUTE_URL);
        $this->logger->debug('Forward to consent', ['url' => $forwardUrl]);
        return new RedirectResponse($forwardUrl);
    }
}
