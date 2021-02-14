<?php

namespace R3H6\Oauth2Server\Domain\Repository;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use R3H6\Oauth2Server\Domain\Model\RefreshToken;

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
 * The repository for RefreshTokens
 */
class RefreshTokenRepository extends \TYPO3\CMS\Extbase\Persistence\Repository implements RefreshTokenRepositoryInterface, LoggerAwareInterface
{
    use QueryBuilderAwareRepositoryTrait;
    use LoggerAwareTrait;

    private const TABLE = 'tx_oauth2server_domain_model_refreshtoken';

    public function getNewRefreshToken()
    {
        $this->logger->debug('Get new refresh token');

        return new RefreshToken();
    }

    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $now = time();
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder
            ->insert(self::TABLE)
            ->values([
                'identifier' => $refreshTokenEntity->getIdentifier(),
                'expires_at' => $refreshTokenEntity->getExpiryDateTime()->getTimestamp(),
                'access_token' => $refreshTokenEntity->getAccessToken()->getIdentifier(),
                'revoked' => 0,
                'crdate' => $now,
                'tstamp' => $now,
            ])
            ->execute();
    }

    public function revokeRefreshToken($tokenId)
    {
        $this->logger->debug('Revoke refresh token', ['identifier' => $tokenId]);

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder
            ->update(self::TABLE)
            ->where(
                $queryBuilder->expr()->eq('identifier', $queryBuilder->createNamedParameter($tokenId))
            )
            ->set('revoked', 1)
            ->set('tstamp', time())
            ->execute();
    }

    public function isRefreshTokenRevoked($tokenId)
    {
        $row = $this->selectOneByIdentifier($tokenId);
        if ($row) {
            return (bool)$row['revoked'];
        }

        return true;
    }
}
