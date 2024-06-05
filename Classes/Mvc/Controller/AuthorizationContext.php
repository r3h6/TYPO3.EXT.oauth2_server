<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Mvc\Controller;

use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Psr\Http\Message\ServerRequestInterface;
use R3H6\Oauth2Server\Configuration\Configuration;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;

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

class AuthorizationContext
{
    public function __construct(
        private readonly ServerRequestInterface $request,
        private readonly AuthorizationRequest $authRequest,
        private readonly Configuration $configuration,
    ) {}

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function getAuthRequest(): AuthorizationRequest
    {
        return $this->authRequest;
    }

    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    public function getFrontendUser(): FrontendUserAuthentication
    {
        return $this->request->getAttribute('frontend.user');
    }

    public function getSite(): Site
    {
        return $this->request->getAttribute('site');
    }

    public function isAuthenticated(): bool
    {
        return $this->getFrontendUserUid() !== null;
    }

    public function getFrontendUserUid(): ?int
    {
        return $this->getFrontendUser()->user['uid'] ?? null;
    }
}
