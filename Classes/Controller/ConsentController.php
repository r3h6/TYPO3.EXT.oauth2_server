<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Controller;

use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Psr\Http\Message\ResponseInterface;
use R3H6\Oauth2Server\Configuration\Configuration;
use R3H6\Oauth2Server\Session\Session;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Error\Http\ForbiddenException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\Controller\ErrorController;

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
        try {
            $authRequest = $this->getAuthRequestOrFail();
        } catch (ForbiddenException $e) {
            return GeneralUtility::makeInstance(ErrorController::class)->accessDeniedAction(
                $this->request,
                $e->getMessage(),
            );
        }

        $this->view->assign('configuration', $this->configuration);
        $this->view->assign('client', $authRequest->getClient());
        $this->view->assign('scopes', $authRequest->getScopes());
        return $this->htmlResponse();
    }

    protected function getAuthRequestOrFail(): AuthorizationRequest
    {
        if (!$this->context->getPropertyFromAspect('frontend.user', 'isLoggedIn')) {
            throw new ForbiddenException('User is not logged in');
        }

        /** @var \League\OAuth2\Server\RequestTypes\AuthorizationRequest|false|null $authRequest */
        $authRequest = Session::fromRequest($this->request)->get();

        if (!$authRequest instanceof AuthorizationRequest) {
            throw new ForbiddenException('No authorization request found in session');
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
