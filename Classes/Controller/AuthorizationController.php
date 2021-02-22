<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Controller;

use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use R3H6\Oauth2Server\Domain\Repository\AccessTokenRepository;
use R3H6\Oauth2Server\Domain\Repository\UserRepository;
use R3H6\Oauth2Server\Http\RequestAttribute;
use R3H6\Oauth2Server\Utility\HashUtility;
use R3H6\Oauth2Server\Utility\ScopeUtility;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Http\Response;
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
    // @todo prompt=none https://auth0.com/docs/authorization/configure-silent-authentication
    public function startAuthorization(ServerRequestInterface $request): ResponseInterface
    {
        /** @var \R3H6\Oauth2Server\Configuration\Configuration configuration */
        $configuration = $request->getAttribute(RequestAttribute::CONFIGURATION);

        /** @var \TYPO3\CMS\Core\Site\Entity\Site $site */
        $site = $request->getAttribute('site');

        /** @var \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication */
        $frontendUser = $request->getAttribute('frontend.user');

        // Validate the HTTP request and return an AuthorizationRequest object.
        $authRequest = $this->server->validateAuthorizationRequest($request);
        $frontendUser->setAndSaveSessionData(self::AUTH_REQUEST_SESSION_KEY, $authRequest);

        /** @var \R3H6\Oauth2Server\Domain\Model\Client $client */
        $client = $authRequest->getClient();
        $isAuthenticated = ($frontendUser->user['uid'] ?? 0) > 0; // Groups are not yet loaded in context api
        $consentPageUid = $configuration->getConsentPageUid();

        $redirectUrl = (string)$request->getUri();
        if ($consentPageUid && !$client->doSkipConsent()) {
            $redirectUrl = (string)$site->getRouter()->generateUri((string)$consentPageUid, [], '', RouterInterface::ABSOLUTE_URL);
        }

        if (!$isAuthenticated) {
            $this->logger->debug('Forward to login', ['redirect_url' => $redirectUrl]);
            $parameters = ['_' => HashUtility::encode($redirectUrl)];
            $loginPageUid = $configuration->getLoginPageUid();
            if ($loginPageUid) {
                $forwardUrl = (string)$site->getRouter()->generateUri((string)$loginPageUid, $parameters, '', RouterInterface::ABSOLUTE_URL);
                return new RedirectResponse($forwardUrl);
            }

            return new RedirectResponse('/?' . http_build_query($parameters));
        }

        $clientId = $client->getIdentifier();
        if ($client->doSkipConsent()) {
            $this->logger->debug('Skip consent', ['clientId' => $clientId]);
            return $this->approveAuthorization($request);
        }

        $user = $this->userRepository->findByUid((int)$frontendUser->user['uid']);
        $userId = $user->getIdentifier();
        $scopes = ScopeUtility::toStrings(...$authRequest->getScopes());
        if ($this->accessTokenRepository->hasValidAccessToken($userId, $clientId, $scopes)) {
            $this->logger->debug('has valid access token', ['userId' => $userId, 'clientId' => $clientId, 'scopes' => $scopes]);
            return $this->approveAuthorization($request);
        }

        return new RedirectResponse($redirectUrl);
    }

    public function approveAuthorization(ServerRequestInterface $request): ResponseInterface
    {
        /** @var \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication */
        $frontendUser = $request->getAttribute('frontend.user');

        /** @var \League\OAuth2\Server\RequestTypes\AuthorizationRequest|null */
        $authRequest = $frontendUser->getSessionData(self::AUTH_REQUEST_SESSION_KEY);
        $frontendUser->setAndSaveSessionData(self::AUTH_REQUEST_SESSION_KEY, null);

        // Once the user has logged in set the user on the AuthorizationRequest
        $user = $this->userRepository->findByUid((int)$frontendUser->user['uid']);

        $authRequest->setUser($user);

        // Consent
        $authRequest->setAuthorizationApproved(true);

        return $this->server->completeAuthorizationRequest($authRequest, new Response());
    }

    public function denyAuthorization(ServerRequestInterface $request): ResponseInterface
    {
        /** @var \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication */
        $frontendUser = $request->getAttribute('frontend.user');

        /** @var \League\OAuth2\Server\RequestTypes\AuthorizationRequest|null */
        $authRequest = $frontendUser->getSessionData(self::AUTH_REQUEST_SESSION_KEY);
        $frontendUser->setAndSaveSessionData(self::AUTH_REQUEST_SESSION_KEY, null);

        // Once the user has logged in set the user on the AuthorizationRequest
        $user = $this->userRepository->findByUid((int)$frontendUser->user['uid']);
        $authRequest->setUser($user);

        // Consent
        $authRequest->setAuthorizationApproved(false);

        return $this->server->completeAuthorizationRequest($authRequest, new Response());
    }
}
