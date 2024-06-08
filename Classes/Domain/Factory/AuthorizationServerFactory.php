<?php

declare(strict_types=1);

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
use League\OAuth2\Server\RequestEvent as OAuth2RequestEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use R3H6\Oauth2Server\Configuration\Configuration;
use R3H6\Oauth2Server\Domain\Bridge\RequestEvent;
use R3H6\Oauth2Server\Event\BeforeAssembleAuthorizationServerEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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

class AuthorizationServerFactory
{
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher) {}

    public function __invoke(Configuration $configuration): AuthorizationServer
    {
        $accessTokenTTL = new \DateInterval($configuration->getAccessTokensExpireIn());

        $event = new BeforeAssembleAuthorizationServerEvent(
            $configuration,
            $this->getClientRepository($configuration),
            $this->getAccessTokenRepository($configuration),
            $this->getScopeRepository($configuration),
            $this->getResponseType($configuration)
        );

        $this->eventDispatcher->dispatch($event);

        $server = GeneralUtility::makeInstance(
            AuthorizationServer::class,
            $event->getClientRepository(),
            $event->getAccessTokenRepository(),
            $event->getScopeRepository(),
            GeneralUtility::getFileAbsFileName($configuration->getPrivateKey()) ?: $configuration->getPrivateKey(),
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'],
            $event->getResponseType()
        );

        $server->enableGrantType($this->getClientCredentialsGrant($configuration), $accessTokenTTL);
        $server->enableGrantType($this->getPasswordGrant($configuration), $accessTokenTTL);
        $server->enableGrantType($this->getAuthCodeGrant($configuration), $accessTokenTTL);
        $server->enableGrantType($this->getRefreshTokenGrant($configuration), $accessTokenTTL);
        $server->enableGrantType($this->getImplicitGrant($configuration), $accessTokenTTL);

        $listener = GeneralUtility::makeInstance(RequestEvent::class);
        $events = [
            OAuth2RequestEvent::USER_AUTHENTICATION_FAILED,
            OAuth2RequestEvent::CLIENT_AUTHENTICATION_FAILED,
            OAuth2RequestEvent::ACCESS_TOKEN_ISSUED,
            OAuth2RequestEvent::REFRESH_TOKEN_ISSUED,
            OAuth2RequestEvent::REFRESH_TOKEN_CLIENT_FAILED,
        ];

        foreach ($events as $event) {
            $server->getEmitter()->addListener($event, $listener);
        }

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
        /** @var AuthCodeGrant $grant */
        $grant = GeneralUtility::makeInstance(
            AuthCodeGrant::class,
            GeneralUtility::makeInstance(AuthCodeRepositoryInterface::class),
            GeneralUtility::makeInstance(RefreshTokenRepositoryInterface::class),
            new \DateInterval('PT10M')
        );

        if ($configuration->getRequireCodeChallengeForPublicClients() === false) {
            $grant->disableRequireCodeChallengeForPublicClients();
        }

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
