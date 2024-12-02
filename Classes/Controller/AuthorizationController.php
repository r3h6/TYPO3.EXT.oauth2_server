<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Controller;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use R3H6\Oauth2Server\Configuration\Configuration;
use R3H6\Oauth2Server\Domain\Model\Client;
use R3H6\Oauth2Server\Domain\Repository\AccessTokenRepository;
use R3H6\Oauth2Server\Domain\Repository\UserRepository;
use R3H6\Oauth2Server\Event\ModifyAuthenticationRedirectEvent;
use R3H6\Oauth2Server\Event\ModifyConsentRedirectEvent;
use R3H6\Oauth2Server\Mvc\Controller\AuthorizationContext;
use R3H6\Oauth2Server\Session\Session;
use R3H6\Oauth2Server\Utility\ScopeUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\SecurityAspect;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Routing\RouterInterface;
use TYPO3\CMS\Core\Security\RequestToken;

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
        private readonly Configuration $configuration,
        private readonly Context $context,
        private readonly EventDispatcherInterface $eventDispatcher,
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

        $modifyAuthenticationRedirectEvent = new ModifyAuthenticationRedirectEvent(
            $this->configuration,
            $context,
            $this->requiresAuthentication($context),
            $this->createAuthenticationRedirect($context)
        );

        $this->eventDispatcher->dispatch($modifyAuthenticationRedirectEvent);

        if ($modifyAuthenticationRedirectEvent->getRequiresAuthentication()) {
            $this->logger->debug('Requires authentication');
            return $modifyAuthenticationRedirectEvent->getResponse();
        }

        $modifyConsentRedirectEvent = new ModifyConsentRedirectEvent(
            $this->configuration,
            $context,
            $this->requiresConsent($context),
            $this->createConsentRedirect($context)
        );

        $this->eventDispatcher->dispatch($modifyConsentRedirectEvent);

        if ($modifyConsentRedirectEvent->getRequiresConsent()) {
            $this->logger->debug('Requires consent');
            return $modifyConsentRedirectEvent->getResponse();
        }

        return $this->finishAuthorization($context, true);
    }

    public function approveAuthorization(ServerRequestInterface $request): ResponseInterface
    {
        $this->validateRequestToken();
        $authRequest = $this->getAuthRequestFromSession($request);
        $context = new AuthorizationContext($request, $authRequest, $this->configuration);
        $this->logger->debug('Approve authorization');
        return $this->finishAuthorization($context, true);
    }

    public function denyAuthorization(ServerRequestInterface $request): ResponseInterface
    {
        $this->validateRequestToken();
        $authRequest = $this->getAuthRequestFromSession($request);
        $context = new AuthorizationContext($request, $authRequest, $this->configuration);
        $this->logger->debug('Deny authorization');
        return $this->finishAuthorization($context, false);
    }

    protected function validateRequestToken(): void
    {
        $securityAspect = SecurityAspect::provideIn($this->context);
        $requestToken = $securityAspect->getReceivedRequestToken();
        if (!$requestToken instanceof RequestToken || $requestToken->scope !== 'oauth2/consent') {
            throw new \RuntimeException('Invalid request token', 1614192910231);
        }
    }

    protected function getAuthRequestFromSession(ServerRequestInterface $request): AuthorizationRequest
    {
        $authRequest = Session::fromRequest($request)->get();
        Session::fromRequest($request)->clear();
        if (!$authRequest instanceof AuthorizationRequest) {
            throw new \RuntimeException('No authorization flow started', 1614192910231);
        }
        return $authRequest;
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
        $user = $this->userRepository->findByUid((int)$context->getFrontendUserUid());
        if ($user === null) {
            throw new \RuntimeException('User not found', 1718223076476);
        }
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
        $this->logger->debug('Builded forward to login url', ['url' => $forwardUrl]);
        return new RedirectResponse($forwardUrl);
    }

    protected function createConsentRedirect(AuthorizationContext $context): ResponseInterface
    {
        $consentPageUid = $context->getConfiguration()->getConsentPageUid();
        $forwardUrl = (string)$context->getSite()->getRouter()->generateUri((string)$consentPageUid, [], '', RouterInterface::ABSOLUTE_URL);
        $this->logger->debug('Builded forward to consent url', ['url' => $forwardUrl]);
        return new RedirectResponse($forwardUrl);
    }
}
