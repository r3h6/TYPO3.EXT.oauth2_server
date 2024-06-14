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
    private readonly FrontendUserAuthentication $frontendUser;
    private readonly Site $site;

    public function __construct(
        private readonly ServerRequestInterface $request,
        private readonly AuthorizationRequest $authRequest,
        private readonly Configuration $configuration,
    ) {
        $this->frontendUser = $this->request->getAttribute('frontend.user') ?: throw new \InvalidArgumentException('Frontend user must be authenticated', 1718222682952);
        $this->site = $this->request->getAttribute('site') ?: throw new \InvalidArgumentException('Site must be set', 1718222689630);
    }

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
        return $this->frontendUser;
    }

    public function getSite(): Site
    {
        return $this->site;
    }

    public function isAuthenticated(): bool
    {
        return $this->getFrontendUserUid() !== null;
    }

    public function getFrontendUserUid(): ?int
    {
        return $this->frontendUser->user['uid'] ?? null;
    }
}
