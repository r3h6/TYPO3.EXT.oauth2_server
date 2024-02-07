<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Controller;

use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use R3H6\Oauth2Server\Configuration\Configuration;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Error\Http\ForbiddenException;
use TYPO3\CMS\Core\Error\Http\PageNotFoundException;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

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
 * Consent plugin
 */
class ConsentController extends ActionController
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var Configuration
     */
    protected $configuration;

    public function showAction(): ResponseInterface
    {
        $authRequest = $this->getAuthRequestOrFail();
        $this->view->assign('configuration', $this->configuration);
        $this->view->assign('client', $authRequest->getClient());
        $this->view->assign('scopes', $authRequest->getScopes());
        return $this->htmlResponse();
    }

    protected function getAuthRequestOrFail(): AuthorizationRequest
    {
        if (!$this->context->getPropertyFromAspect('frontend.user', 'isLoggedIn')) {
            throw new ForbiddenException();
        }

        /** @var AuthorizationRequest|false|null $authRequest */
        $authRequest = unserialize($GLOBALS['TSFE']->fe_user->getSessionData(AuthorizationController::AUTH_REQUEST_SESSION_KEY) ?? '');

        if (!$authRequest instanceof AuthorizationRequest) {
            throw new PageNotFoundException();
        }
        return $authRequest;
    }

    public function injectContext(Context $context): void
    {
        $this->context = $context;
    }

    public function injectConfiguration(Configuration $configuration): void
    {
        $this->configuration = $configuration;
    }
}
