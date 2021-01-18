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

    public function indexAction()
    {
        $authRequest = $this->getAuthRequestOrFail();
        $this->view->assign('client', $authRequest->getClient());
        $this->view->assign('scopes', $authRequest->getScopes());
    }

    public function denyAction()
    {
        $authRequest = $this->getAuthRequestOrFail();
        $authRequest->setAuthorizationApproved(false);
        $this->frontendUser->setAndSaveSessionData('oauth2/authRequest', $authRequest);
        $this->redirectToUri('/oauth/authorization');
    }

    public function acceptAction()
    {
        $authRequest = $this->getAuthRequestOrFail();
        $authRequest->setAuthorizationApproved(true);
        $this->frontendUser->setAndSaveSessionData('oauth2/authRequest', $authRequest);
        $this->redirectToUri('/oauth/authorization');
    }

    protected function getAuthRequestOrFail(): AuthorizationRequest
    {
        /** @var \League\OAuth2\Server\RequestTypes\AuthorizationRequest */
        $authRequest = $this->frontendUser->getSessionData('oauth2/authRequest');
        if (!$authRequest instanceof AuthorizationRequest) {
            throw new PageNotFoundException();
        }
        return $authRequest;
    }
}
