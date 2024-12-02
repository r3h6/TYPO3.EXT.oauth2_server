<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Event;

use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\ImplicitGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use R3H6\Oauth2Server\Configuration\Configuration;

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

final class BeforeAssembleAuthorizationServerEvent
{
    public function __construct(
        private readonly Configuration $configuration,
        private ClientRepositoryInterface $clientRepository,
        private AccessTokenRepositoryInterface $accessTokenRepository,
        private ScopeRepositoryInterface $scopeRepository,
        private ?ResponseTypeInterface $responseType,
        private ?ClientCredentialsGrant $clientCredentialsGrant,
        private ?PasswordGrant $passwordGrant,
        private ?AuthCodeGrant $authCodeGrant,
        private ?RefreshTokenGrant $refreshTokenGrant,
        private ?ImplicitGrant $implicitGrant,
    ) {}

    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    public function getClientRepository(): ClientRepositoryInterface
    {
        return $this->clientRepository;
    }

    public function setClientRepository(ClientRepositoryInterface $clientRepository): void
    {
        $this->clientRepository = $clientRepository;
    }

    public function getAccessTokenRepository(): AccessTokenRepositoryInterface
    {
        return $this->accessTokenRepository;
    }

    public function setAccessTokenRepository(AccessTokenRepositoryInterface $accessTokenRepository): void
    {
        $this->accessTokenRepository = $accessTokenRepository;
    }

    public function getScopeRepository(): ScopeRepositoryInterface
    {
        return $this->scopeRepository;
    }

    public function setScopeRepository(ScopeRepositoryInterface $scopeRepository): void
    {
        $this->scopeRepository = $scopeRepository;
    }

    public function getResponseType(): ?ResponseTypeInterface
    {
        return $this->responseType;
    }

    public function setResponseType(?ResponseTypeInterface $responseType): void
    {
        $this->responseType = $responseType;
    }

    public function getClientCredentialsGrant(): ?ClientCredentialsGrant
    {
        return $this->clientCredentialsGrant;
    }

    public function setClientCredentialsGrant(?ClientCredentialsGrant $clientCredentialsGrant): void
    {
        $this->clientCredentialsGrant = $clientCredentialsGrant;
    }

    public function getPasswordGrant(): ?PasswordGrant
    {
        return $this->passwordGrant;
    }

    public function setPasswordGrant(?PasswordGrant $passwordGrant): void
    {
        $this->passwordGrant = $passwordGrant;
    }

    public function getAuthCodeGrant(): ?AuthCodeGrant
    {
        return $this->authCodeGrant;
    }

    public function setAuthCodeGrant(?AuthCodeGrant $authCodeGrant): void
    {
        $this->authCodeGrant = $authCodeGrant;
    }

    public function getRefreshTokenGrant(): ?RefreshTokenGrant
    {
        return $this->refreshTokenGrant;
    }

    public function setRefreshTokenGrant(?RefreshTokenGrant $refreshTokenGrant): void
    {
        $this->refreshTokenGrant = $refreshTokenGrant;
    }

    public function getImplicitGrant(): ?ImplicitGrant
    {
        return $this->implicitGrant;
    }

    public function setImplicitGrant(?ImplicitGrant $implicitGrant): void
    {
        $this->implicitGrant = $implicitGrant;
    }
}
