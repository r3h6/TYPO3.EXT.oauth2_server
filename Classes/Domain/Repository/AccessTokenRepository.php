<?php
namespace R3H6\Oauth2Server\Domain\Repository;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerAwareInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use R3H6\Oauth2Server\Utility\ScopeUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use R3H6\Oauth2Server\Domain\Model\AccessToken;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

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
 * The repository for AccessTokens
 */
class AccessTokenRepository extends \TYPO3\CMS\Extbase\Persistence\Repository implements AccessTokenRepositoryInterface, LoggerAwareInterface
{
    use QueryBuilderAwareRepositoryTrait;
    use LoggerAwareTrait;

    private const TABLE = 'tx_oauth2server_domain_model_accesstoken';

    /**
     * @override
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        $this->logger->debug('Get new token', ['client' => $clientEntity->getIdentifier(), 'scopes' => $scopes, 'userIdentifier' => $userIdentifier]);

        $accessToken = new AccessToken();
        $accessToken->setClient($clientEntity);
        $accessToken->setUserIdentifier($userIdentifier);
        $accessToken->setExpiryDateTime(\DateTimeImmutable::createFromMutable((new \DateTime())->add(new \DateInterval('PT6H'))));

        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }

        return $accessToken;
    }


    /**
     * @override
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder
            ->insert(self::TABLE)
            ->values([
                'identifier' => $accessTokenEntity->getIdentifier(),
                'expires_at' => $accessTokenEntity->getExpiryDateTime()->getTimestamp(),
                'user' => $accessTokenEntity->getUserIdentifier(),
                'scopes' => ScopeUtility::toString(...$accessTokenEntity->getScopes()),
                'client' => $accessTokenEntity->getClient()->getIdentifier(),
                'revoked' => 0,
            ])
            ->execute();
    }

    /**
     * @override
     */
    public function revokeAccessToken($tokenId)
    {
        $this->logger->debug('Revoke access token', ['identifier' => $tokenId]);

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder
            ->update(self::TABLE)
            ->where(
                $queryBuilder->expr()->eq('identifier', $queryBuilder->createNamedParameter($tokenId))
            )
            ->set('revoked', 1)
            ->execute();
    }

    /**
     * @override
     */
    public function isAccessTokenRevoked($tokenId)
    {
        $row = $this->selectOneByIdentifier($tokenId);
        if ($row) {
            return (bool) $row['revoked'];
        }

        return true;
    }

    /**
     * @param string|int $userId
     * @param string|int $clientId
     * @param string[] $scopes
     */
    public function hasValidAccessToken($userId, $clientId, array $scopes)
    {
        $queryBuilder = $this->createQueryBuilder();
        $row = $queryBuilder
            ->select('*')
            ->from(self::TABLE)
            ->where(
                $queryBuilder->expr()->eq('user', $queryBuilder->createNamedParameter($userId)),
                $queryBuilder->expr()->eq('client', $queryBuilder->createNamedParameter($clientId)),
                $queryBuilder->expr()->eq('revoked', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT)),
            )
            ->orderBy('expires_at', 'DESC')
            ->execute()
            ->fetch();

        return ($row && array_diff(GeneralUtility::trimExplode(',', $row['scopes']), $scopes) === []);
    }
}
