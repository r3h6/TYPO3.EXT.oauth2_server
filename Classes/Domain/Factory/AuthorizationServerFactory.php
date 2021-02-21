<?php

namespace R3H6\Oauth2Server\Domain\Factory;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\ImplicitGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use R3H6\Oauth2Server\Configuration\Configuration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AuthorizationServerFactory implements AuthorizationServerFactoryInterface
{
    public function __invoke(Configuration $configuration): AuthorizationServer
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
        $server->enableGrantType($this->getImplicitGrant($configuration), $accessTokenTTL);


        return $server;
    }

    protected function getClientRepository(Configuration $configuration): ClientRepositoryInterface
    {
        return GeneralUtility::makeInstance(ClientRepositoryInterface::class);
    }

    protected function getAccessTokenRepository(Configuration $configuration): AccessTokenRepositoryInterface
    {
        return GeneralUtility::makeInstance(AccessTokenRepositoryInterface::class);
    }

    protected function getScopeRepository(Configuration $configuration): ScopeRepositoryInterface
    {
        return GeneralUtility::makeInstance(ScopeRepositoryInterface::class);
    }

    protected function getResponseType(Configuration $configuration): ?ResponseTypeInterface
    {
        return null;
    }

    protected function getClientCredentialsGrant(Configuration $configuration): ClientCredentialsGrant
    {
        return GeneralUtility::makeInstance(ClientCredentialsGrant::class);
    }

    protected function getPasswordGrant(Configuration $configuration): PasswordGrant
    {
        $grant = GeneralUtility::makeInstance(
            PasswordGrant::class,
            GeneralUtility::makeInstance(UserRepositoryInterface::class),
            GeneralUtility::makeInstance(RefreshTokenRepositoryInterface::class)
        );

        $refreshTokenTTL = new \DateInterval($configuration->getRefreshTokensExpireIn());
        $grant->setRefreshTokenTTL($refreshTokenTTL);
        return $grant;
    }

    protected function getAuthCodeGrant(Configuration $configuration): AuthCodeGrant
    {
        $grant = GeneralUtility::makeInstance(
            AuthCodeGrant::class,
            GeneralUtility::makeInstance(AuthCodeRepositoryInterface::class),
            GeneralUtility::makeInstance(RefreshTokenRepositoryInterface::class),
            new \DateInterval('PT10M')
        );

        $refreshTokenTTL = new \DateInterval($configuration->getRefreshTokensExpireIn());
        $grant->setRefreshTokenTTL($refreshTokenTTL);
        return $grant;
    }

    protected function getImplicitGrant(Configuration $configuration): ImplicitGrant
    {
        $accessTokenTTL = new \DateInterval('PT1H');
        return GeneralUtility::makeInstance(ImplicitGrant::class, $accessTokenTTL);
    }

    protected function getRefreshTokenGrant(Configuration $configuration): RefreshTokenGrant
    {
        $grant = GeneralUtility::makeInstance(
            RefreshTokenGrant::class,
            GeneralUtility::makeInstance(RefreshTokenRepositoryInterface::class)
        );

        $refreshTokenTTL = new \DateInterval($configuration->getRefreshTokensExpireIn());
        $grant->setRefreshTokenTTL($refreshTokenTTL);
        return $grant;
    }
}
