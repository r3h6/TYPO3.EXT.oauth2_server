<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Domain\Bridge;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use R3H6\Oauth2Server\Utility\ScopeUtility;
use TYPO3\CMS\Core\SingletonInterface;
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

class AccessTokenRepository implements SingletonInterface, AccessTokenRepositoryInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(private \R3H6\Oauth2Server\Domain\Repository\AccessTokenRepository $repository) {}

    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, ?string $userIdentifier = null): AccessTokenEntityInterface
    {
        $this->logger->debug('Get new token', ['client' => $clientEntity->getIdentifier(), 'scopes' => $scopes, 'userIdentifier' => $userIdentifier]);

        $accessToken = new AccessToken();
        $accessToken->setClient($clientEntity);
        if ($userIdentifier !== null && $userIdentifier !== '') {
            $accessToken->setUserIdentifier($userIdentifier);
        }
        $accessToken->setExpiryDateTime(\DateTimeImmutable::createFromMutable((new \DateTime())->add(new \DateInterval('PT6H'))));

        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }

        return $accessToken;
    }

    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void
    {
        $newToken = GeneralUtility::makeInstance(\R3H6\Oauth2Server\Domain\Model\AccessToken::class);
        $newToken->setPid(0);
        $newToken->setIdentifier($accessTokenEntity->getIdentifier());
        $newToken->setExpiresAt($accessTokenEntity->getExpiryDateTime());
        $newToken->setUser((string)$accessTokenEntity->getUserIdentifier());
        $newToken->setScopes(ScopeUtility::toString(...$accessTokenEntity->getScopes()));
        $newToken->setClient((string)$accessTokenEntity->getClient()->getIdentifier());
        $this->repository->add($newToken);
        $this->repository->persist();
    }

    public function revokeAccessToken(string $tokenId): void
    {
        $this->logger->debug('Revoke access token', ['identifier' => $tokenId]);
        $token = $this->repository->findOneBy(['identifier' => $tokenId]);
        if (!$token) {
            $this->logger->warning('Access token not found', ['identifier' => $tokenId]);
            return;
        }
        $token->setRevoked(true);
        $this->repository->update($token);
        $this->repository->persist();
    }

    public function isAccessTokenRevoked(string $tokenId): bool
    {
        $token = $this->repository->findOneBy(['identifier' => $tokenId]);
        if ($token) {
            return $token->getRevoked();
        }
        $this->logger->warning('Access token not found', ['identifier' => $tokenId]);
        return true;
    }
}
