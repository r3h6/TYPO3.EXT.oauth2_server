<?php

namespace R3H6\Oauth2Server\Controller;

use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Http\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\AuthorizationServer;
use R3H6\Oauth2Server\Domain\Repository\UserRepository;

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

    public function authenticateAction(ServerRequestInterface $request): ResponseInterface
    {
        // Validate the HTTP request and return an AuthorizationRequest object.
        $authRequest = $this->server->validateAuthorizationRequest($request);
        // $authRequest->setRedirectUri($request->getQueryParams()['redirect_uri']);

        // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($authRequest); exit;

        /** @var \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication */
        $frontendUser = $request->getAttribute('frontend.user');
        $frontendUser->setAndSaveSessionData('oauth2/authRequest', $authRequest);

        // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($authRequest); exit;
        // The auth request object can be serialized and saved into a user's session.
        // You will probably want to redirect the user at this point to a login endpoint.


        $view = new \TYPO3Fluid\Fluid\View\TemplateView();
        $view->getTemplatePaths()->setTemplatePathAndFilename(
            GeneralUtility::getFileAbsFileName('EXT:oauth2_server/Resources/Private/Templates/Oauth/Authenticate.html')
        );

        return new HtmlResponse($view->render());
    }

    public function authorizeAction(ServerRequestInterface $request): ResponseInterface
    {
        /** @var \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication */
        $frontendUser = $request->getAttribute('frontend.user');

        /** @var \League\OAuth2\Server\RequestTypes\AuthorizationRequest */
        $authRequest = $frontendUser->getSessionData('oauth2/authRequest');

        $user = $this->userRepository->findByUid((int)$frontendUser->user['uid']);

        // Once the user has logged in set the user on the AuthorizationRequest
        $authRequest->setUser($user); // an instance of UserEntityInterface



        $frontendUser->setAndSaveSessionData('oauth2/authRequest', $authRequest);


        // At this point you should redirect the user to an authorization page.
        // This form will ask the user to approve the client and the scopes requested.



        $view = new \TYPO3Fluid\Fluid\View\TemplateView();
        $view->getTemplatePaths()->setTemplatePathAndFilename(
            GeneralUtility::getFileAbsFileName('EXT:oauth2_server/Resources/Private/Templates/Oauth/Authorize.html')
        );
        $view->assign('client', $authRequest->getClient());
        $view->assign('scopes', $authRequest->getScopes());

        return new HtmlResponse($view->render());
    }

    public function approveAction(ServerRequestInterface $request): ResponseInterface
    {
        /** @var \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication */
        $frontendUser = $request->getAttribute('frontend.user');
        $authRequest = $frontendUser->getSessionData('oauth2/authRequest');


        $response = new Response();
        // Once the user has approved or denied the client update the status
        // (true = approved, false = denied)
        $authRequest->setAuthorizationApproved(true);

        // Return the HTTP redirect response
        return $this->server->completeAuthorizationRequest($authRequest, $response);
    }

    public function denyAction(ServerRequestInterface $request): ResponseInterface
    {
        return new Response();
    }

    public function accessTokenAction(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->server->respondToAccessTokenRequest($request, new Response());
        return $response;
    }
}
