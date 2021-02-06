<?php

namespace R3H6\Oauth2Server\Controller;

use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use TYPO3\CMS\Core\Error\Http\PageNotFoundException;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ConsentController extends ActionController
{
    /**
     * @var \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication
     */
    protected $frontendUser;

    protected function initializeAction()
    {
        $this->frontendUser = $GLOBALS['TSFE']->fe_user;
    }

    public function showAction()
    {
        $authRequest = $this->getAuthRequestOrFail();
        $this->view->assign('client', $authRequest->getClient());
        $this->view->assign('scopes', $authRequest->getScopes());
    }

    protected function getAuthRequestOrFail(): AuthorizationRequest
    {
        /** @var \League\OAuth2\Server\RequestTypes\AuthorizationRequest */
        $authRequest = $this->frontendUser->getSessionData(AuthorizationController::AUTH_REQUEST_SESSION_KEY);
        if (!$authRequest instanceof AuthorizationRequest) {
            throw new PageNotFoundException();
        }
        return $authRequest;
    }
}
