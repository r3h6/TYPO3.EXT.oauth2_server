<?php

namespace R3H6\Oauth2Server\Controller;

use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Http\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\AuthorizationServer;
use R3H6\Oauth2Server\Domain\Repository\UserRepository;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class AuthorizationController
{
    /**
     * @var \R3H6\Oauth2Server\Domain\Repository\UserRepository
     */
    protected $userRepository;

    /**
     * @var \League\OAuth2\Server\AuthorizationServer
     */
    protected $server;

    public function __construct(AuthorizationServer $server, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->server = $server;
    }

    public function startAuthorization(ServerRequestInterface $request): ResponseInterface
    {
        /** @var \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication */
        $frontendUser = $request->getAttribute('frontend.user');

        // Validate the HTTP request and return an AuthorizationRequest object.
        $authRequest = $this->server->validateAuthorizationRequest($request);
        $frontendUser->setAndSaveSessionData('oauth2/authRequest', $authRequest);

        $isAuthenticated = ($frontendUser->user['uid'] ?? 0) > 0; // Groups not loaded in context api
        if (!$isAuthenticated) {
            return new RedirectResponse('/?redirect_url='.urlencode('/consent'));
        }

        return new RedirectResponse('/consent');
    }

    public function approveAuthorization(ServerRequestInterface $request): ResponseInterface
    {
       /** @var \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication */
       $frontendUser = $request->getAttribute('frontend.user');

       /** @var \League\OAuth2\Server\RequestTypes\AuthorizationRequest|null */
       $authRequest = $frontendUser->getSessionData('oauth2/authRequest');

       // Once the user has logged in set the user on the AuthorizationRequest
       $user = $this->userRepository->findByUid((int)$frontendUser->user['uid']);
       $authRequest->setUser($user);

       // Consent
       $authRequest->setAuthorizationApproved(true);

       $response = new Response();
       return $this->server->completeAuthorizationRequest($authRequest, $response);
    }

    public function denyAuthorization(ServerRequestInterface $request): ResponseInterface
    {
        /** @var \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication */
        $frontendUser = $request->getAttribute('frontend.user');

        /** @var \League\OAuth2\Server\RequestTypes\AuthorizationRequest|null */
        $authRequest = $frontendUser->getSessionData('oauth2/authRequest');

        // Once the user has logged in set the user on the AuthorizationRequest
        $user = $this->userRepository->findByUid((int)$frontendUser->user['uid']);
        $authRequest->setUser($user);

        // Consent
        $authRequest->setAuthorizationApproved(false);

        $response = new Response();
        return $this->server->completeAuthorizationRequest($authRequest, $response);
    }
}
