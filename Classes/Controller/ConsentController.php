<?php

namespace R3H6\Oauth2Server\Controller;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Error\Http\ForbiddenException;
use TYPO3\CMS\Core\Error\Http\PageNotFoundException;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;

class ConsentController extends ActionController
{
    /**
     * @var Context
     */
    protected $context;

    public function injectContext(Context $context)
    {
        $this->context = $context;
    }

    public function showAction()
    {
        $authRequest = $this->getAuthRequestOrFail();
        $this->view->assign('client', $authRequest->getClient());
        $this->view->assign('scopes', $authRequest->getScopes());
    }

    protected function getAuthRequestOrFail(): AuthorizationRequest
    {
        if (!$this->context->getPropertyFromAspect('frontend.user', 'isLoggedIn')) {
            throw new ForbiddenException();
        }

        /** @var \League\OAuth2\Server\RequestTypes\AuthorizationRequest */
        $authRequest = $GLOBALS['TSFE']->fe_user->getSessionData(AuthorizationController::AUTH_REQUEST_SESSION_KEY);

        if (!$authRequest instanceof AuthorizationRequest) {
            throw new PageNotFoundException();
        }
        return $authRequest;
    }
}
