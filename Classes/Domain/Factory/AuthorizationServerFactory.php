<?php

namespace R3H6\Oauth2Server\Domain\Factory;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ImplicitGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use R3H6\Oauth2Server\Domain\Repository\UserRepository;
use R3H6\Oauth2Server\Domain\Repository\ScopeRepository;
use R3H6\Oauth2Server\Configuration\RuntimeConfiguration;
use R3H6\Oauth2Server\Domain\Repository\ClientRepository;
use R3H6\Oauth2Server\Domain\Repository\AuthCodeRepository;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use R3H6\Oauth2Server\Domain\Repository\AccessTokenRepository;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use R3H6\Oauth2Server\Domain\Repository\RefreshTokenRepository;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

class AuthorizationServerFactory implements SingletonInterface
{

    public function __invoke(RuntimeConfiguration $configuration)
    {
        $accessTokenTTL = new \DateInterval(
            $configuration->get('server.tokensExpireIn')
        );

        $server = GeneralUtility::makeInstance(
            AuthorizationServer::class,
            $this->getClientRepository(),
            $this->getAccessTokenRepository(),
            $this->getScopeRepository(),
            GeneralUtility::getFileAbsFileName($configuration->get('privateKey')),
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'],
            $this->getResponseType()
        );

        $server->enableGrantType($this->getClientCredentialsGrant(), $accessTokenTTL);
        $server->enableGrantType($this->getPasswordGrant(), $accessTokenTTL);
        $server->enableGrantType($this->getAuthCodeGrant(), $accessTokenTTL);
        $server->enableGrantType($this->getRefreshTokenGrant(), $accessTokenTTL);

        if ($configuration->get('server.grantTypes.implicit.enabled')) {
            $server->enableGrantType($this->getImplicitGrant(), $accessTokenTTL);
        }

        return $server;
    }

    protected function getClientRepository(): ClientRepositoryInterface
    {
        return GeneralUtility::makeInstance(ClientRepository::class);
    }

    protected function getAccessTokenRepository(): AccessTokenRepositoryInterface
    {
        return GeneralUtility::makeInstance(AccessTokenRepository::class);
    }

    protected function getScopeRepository(): ScopeRepositoryInterface
    {
        return GeneralUtility::makeInstance(ScopeRepository::class);
    }

    protected function getResponseType(): ?ResponseTypeInterface
    {
        return null;
    }

    protected function getClientCredentialsGrant(): ClientCredentialsGrant
    {
        return GeneralUtility::makeInstance(ClientCredentialsGrant::class);
    }

    protected function getPasswordGrant (): PasswordGrant
    {
        $grant = GeneralUtility::makeInstance(
            PasswordGrant::class,
            GeneralUtility::makeInstance(UserRepository::class),
            GeneralUtility::makeInstance(RefreshTokenRepository::class)
        );
        $grant->setRefreshTokenTTL(new \DateInterval('P1M'));
        return $grant;
    }

    protected function getAuthCodeGrant (): AuthCodeGrant
    {
        $grant = GeneralUtility::makeInstance(
            AuthCodeGrant::class,
            GeneralUtility::makeInstance(AuthCodeRepository::class),
            GeneralUtility::makeInstance(RefreshTokenRepository::class),
            new \DateInterval('PT10M')
        );

        $grant->setRefreshTokenTTL(new \DateInterval('P1M'));
        return $grant;
    }

    protected function getImplicitGrant(): ImplicitGrant
    {
        return GeneralUtility::makeInstance(ImplicitGrant::class, new \DateInterval('PT1H'));
    }

    protected function getRefreshTokenGrant(): RefreshTokenGrant
    {
        $grant = GeneralUtility::makeInstance(
            RefreshTokenGrant::class,
            GeneralUtility::makeInstance(RefreshTokenRepository::class)
        );
        $grant->setRefreshTokenTTL(new \DateInterval('P1M'));
        return $grant;
    }
}
