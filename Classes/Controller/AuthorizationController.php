<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Controller;

use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use R3H6\Oauth2Server\Domain\Model\Client;
use R3H6\Oauth2Server\Domain\Repository\AccessTokenRepository;
use R3H6\Oauth2Server\Domain\Repository\UserRepository;
use R3H6\Oauth2Server\Http\RequestAttribute;
use R3H6\Oauth2Server\Mvc\Controller\AuthorizationContext;
use R3H6\Oauth2Server\Utility\ScopeUtility;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Routing\RouterInterface;
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
  * Authorization endpoint
  */
class AuthorizationController implements LoggerAwareInterface
{
    public const AUTH_REQUEST_SESSION_KEY = 'oauth2/authRequest';

    use LoggerAwareTrait;

    /**
     * @var \R3H6\Oauth2Server\Domain\Repository\UserRepository
     */
    protected $userRepository;

    /**
     * @var \R3H6\Oauth2Server\Domain\Repository\AccessTokenRepository
     */
    protected $accessTokenRepository;

    /**
     * @var AuthorizationServer
     */
    protected $server;

    public function __construct(UserRepository $userRepository, AccessTokenRepository $accessTokenRepository, AuthorizationServer $server)
    {
        $this->userRepository = $userRepository;
        $this->accessTokenRepository = $accessTokenRepository;
        $this->server = $server;
    }

    public function startAuthorization(ServerRequestInterface $request): ResponseInterface
    {
        $this->logger->debug('Start authorization');
        $context = $this->createContext($request);

        // Validate the HTTP request and return an AuthorizationRequest object.
        $authRequest = $this->server->validateAuthorizationRequest($request);
        $context->setAuthRequest($authRequest);

        // Check if user is logged in, if so, add user to authorization request.
        if ($context->isAuthenticated()) {
            $user = $this->userRepository->findByUid($context->getFrontendUserUid());
            $this->logger->debug('Set user to authorization request', ['user' => $user]);
            $authRequest->setUser($user);
        }

        $context->getFrontendUser()->setAndSaveSessionData(self::AUTH_REQUEST_SESSION_KEY, $authRequest);

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
        $this->logger->debug('Approve authorization');
        $context = $this->createContext($request);
        return $this->finishAuthorization($context, true);
    }

    public function denyAuthorization(ServerRequestInterface $request): ResponseInterface
    {
        $this->logger->debug('Deny authorization');
        $context = $this->createContext($request);
        return $this->finishAuthorization($context, false);
    }

    protected function finishAuthorization(AuthorizationContext $context, bool $approved): ResponseInterface
    {
        $frontendUser = $context->getFrontendUser();

        /** @var \League\OAuth2\Server\RequestTypes\AuthorizationRequest|null */
        $authRequest = $frontendUser->getSessionData(self::AUTH_REQUEST_SESSION_KEY);
        $frontendUser->setAndSaveSessionData(self::AUTH_REQUEST_SESSION_KEY, null);

        if ($authRequest === null) {
            throw new \RuntimeException('Try to approve authorization without starting it', 1614192910231);
        }

        if ($authRequest->getUser() === null) {
            throw new \RuntimeException('Try to approve authorization request without user', 1614192781931);
        }

        $authRequest->setAuthorizationApproved($approved);

        return $this->server->completeAuthorizationRequest($authRequest, new Response());
    }

    protected function createContext(ServerRequestInterface $request)
    {
        $context = GeneralUtility::makeInstance(AuthorizationContext::class);
        $context->setRequest($request);
        $context->setSite($request->getAttribute('site'));
        $context->setFrontendUser($request->getAttribute('frontend.user'));
        $context->setConfiguration($request->getAttribute(RequestAttribute::CONFIGURATION));
        return $context;
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
        $this->logger->debug('Forward to login');
        $selfUrl = (string)$context->getRequest()->getUri();
        $parameters = ['redirect_url' => $selfUrl];
        $loginPageUid = $context->getConfiguration()->getLoginPageUid();
        if ($loginPageUid) {
            $forwardUrl = (string)$context->getSite()->getRouter()->generateUri((string)$loginPageUid, $parameters, '', RouterInterface::ABSOLUTE_URL);
            return new RedirectResponse($forwardUrl);
        }

        return new RedirectResponse('/?' . http_build_query($parameters));
    }

    protected function createConsentRedirect(AuthorizationContext $context): ResponseInterface
    {
        $this->logger->debug('Forward to consent');
        $consentPageUid = $context->getConfiguration()->getConsentPageUid();
        $forwardUrl = (string)$context->getSite()->getRouter()->generateUri((string)$consentPageUid, [], '', RouterInterface::ABSOLUTE_URL);
        return new RedirectResponse($forwardUrl);
    }
}
