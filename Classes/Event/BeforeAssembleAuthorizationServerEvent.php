<?php

namespace R3H6\Oauth2Server\Event;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use R3H6\Oauth2Server\Configuration\Configuration;

final class BeforeAssembleAuthorizationServerEvent
{
    public function __construct(
        private readonly Configuration $configuration,
        private ClientRepositoryInterface $clientRepository,
        private AccessTokenRepositoryInterface $accessTokenRepository,
        private ScopeRepositoryInterface $scopeRepository,
        private ?ResponseTypeInterface $responseType
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
}
