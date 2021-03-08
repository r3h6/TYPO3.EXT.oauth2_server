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

 /**
  * AuthorizationContext
  */
class AuthorizationContext
{
    /** @var ServerRequestInterface */
    private $request;

    /** @var AuthorizationRequest */
    private $authRequest;

    /** @var Configuration */
    private $configuration;

    /** @var FrontendUserAuthentication */
    private $frontendUser;

    /** @var Site */
    private $site;

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function setRequest(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function getAuthRequest(): AuthorizationRequest
    {
        return $this->authRequest;
    }

    public function setAuthRequest(AuthorizationRequest $authRequest)
    {
        $this->authRequest = $authRequest;
    }

    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    public function setConfiguration(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getFrontendUser(): FrontendUserAuthentication
    {
        return $this->frontendUser;
    }

    public function setFrontendUser(FrontendUserAuthentication $frontendUser)
    {
        $this->frontendUser = $frontendUser;
    }

    public function getSite(): Site
    {
        return $this->site;
    }

    public function setSite(Site $site)
    {
        $this->site = $site;
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
