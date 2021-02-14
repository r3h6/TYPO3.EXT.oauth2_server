<?php

namespace R3H6\Oauth2Server\Domain\Factory;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\ImplicitGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use R3H6\Oauth2Server\Configuration\Oauth2Configuration;
use R3H6\Oauth2Server\Domain\Repository\AccessTokenRepository;
use R3H6\Oauth2Server\Domain\Repository\AuthCodeRepository;
use R3H6\Oauth2Server\Domain\Repository\ClientRepository;
use R3H6\Oauth2Server\Domain\Repository\RefreshTokenRepository;
use R3H6\Oauth2Server\Domain\Repository\ScopeRepository;
use R3H6\Oauth2Server\Domain\Repository\UserRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AuthorizationServerFactory
{
    public function __invoke(Oauth2Configuration $configuration)
    {
        $accessTokenTTL = new \DateInterval($configuration->getAccessTokensExpireIn());
        $server = GeneralUtility::makeInstance(
            AuthorizationServer::class,
            $this->getClientRepository($configuration),
            $this->getAccessTokenRepository($configuration),
            $this->getScopeRepository($configuration),
            GeneralUtility::getFileAbsFileName($configuration->getPrivateKey()),
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'],
            $this->getResponseType($configuration)
        );

        $server->enableGrantType($this->getClientCredentialsGrant($configuration), $accessTokenTTL);
        $server->enableGrantType($this->getPasswordGrant($configuration), $accessTokenTTL);
        $server->enableGrantType($this->getAuthCodeGrant($configuration), $accessTokenTTL);
        $server->enableGrantType($this->getRefreshTokenGrant($configuration), $accessTokenTTL);

        if ($configuration->getEnableImplicitGrantType()) {
            $server->enableGrantType($this->getImplicitGrant($configuration), $accessTokenTTL);
        }

        return $server;
    }

    protected function getClientRepository(Oauth2Configuration $configuration): ClientRepositoryInterface
    {
        return GeneralUtility::makeInstance(ClientRepository::class);
    }

    protected function getAccessTokenRepository(Oauth2Configuration $configuration): AccessTokenRepositoryInterface
    {
        return GeneralUtility::makeInstance(AccessTokenRepository::class);
    }

    protected function getScopeRepository(Oauth2Configuration $configuration): ScopeRepositoryInterface
    {
        return GeneralUtility::makeInstance(ScopeRepository::class);
    }

    protected function getResponseType(Oauth2Configuration $configuration): ?ResponseTypeInterface
    {
        return null;
    }

    protected function getClientCredentialsGrant(Oauth2Configuration $configuration): ClientCredentialsGrant
    {
        return GeneralUtility::makeInstance(ClientCredentialsGrant::class);
    }

    protected function getPasswordGrant(Oauth2Configuration $configuration): PasswordGrant
    {
        $grant = GeneralUtility::makeInstance(
            PasswordGrant::class,
            GeneralUtility::makeInstance(UserRepository::class),
            GeneralUtility::makeInstance(RefreshTokenRepository::class)
        );

        $refreshTokenTTL = new \DateInterval($configuration->getRefreshTokensExpireIn());
        $grant->setRefreshTokenTTL($refreshTokenTTL);
        return $grant;
    }

    protected function getAuthCodeGrant(Oauth2Configuration $configuration): AuthCodeGrant
    {
        $grant = GeneralUtility::makeInstance(
            AuthCodeGrant::class,
            GeneralUtility::makeInstance(AuthCodeRepository::class),
            GeneralUtility::makeInstance(RefreshTokenRepository::class),
            new \DateInterval('PT10M')
        );

        $refreshTokenTTL = new \DateInterval($configuration->getRefreshTokensExpireIn());
        $grant->setRefreshTokenTTL($refreshTokenTTL);
        return $grant;
    }

    protected function getImplicitGrant(Oauth2Configuration $configuration): ImplicitGrant
    {
        $accessTokenTTL = new \DateInterval('PT1H');
        return GeneralUtility::makeInstance(ImplicitGrant::class, $accessTokenTTL);
    }

    protected function getRefreshTokenGrant(Oauth2Configuration $configuration): RefreshTokenGrant
    {
        $grant = GeneralUtility::makeInstance(
            RefreshTokenGrant::class,
            GeneralUtility::makeInstance(RefreshTokenRepository::class)
        );

        $refreshTokenTTL = new \DateInterval($configuration->getRefreshTokensExpireIn());
        $grant->setRefreshTokenTTL($refreshTokenTTL);
        return $grant;
    }
}
