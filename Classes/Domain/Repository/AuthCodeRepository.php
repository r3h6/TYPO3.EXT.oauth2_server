<?php
namespace R3H6\Oauth2Server\Domain\Repository;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerAwareInterface;
use TYPO3\CMS\Core\Database\Connection;
use R3H6\Oauth2Server\Utility\ScopeUtility;
use R3H6\Oauth2Server\Domain\Model\AuthCode;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

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
 * The repository for AuthCodes
 */
class AuthCodeRepository extends \TYPO3\CMS\Extbase\Persistence\Repository implements AuthCodeRepositoryInterface, LoggerAwareInterface
{
    use QueryBuilderAwareRepositoryTrait;
    use LoggerAwareTrait;

    private const TABLE = 'tx_oauth2server_domain_model_authcode';

    public function getNewAuthCode()
    {
        $this->logger->debug('Get new auth code');

        return new AuthCode();
    }

    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder
            ->insert(self::TABLE)
            ->values([
                'identifier' => $authCodeEntity->getIdentifier(),
                'expires_at' => $authCodeEntity->getExpiryDateTime()->getTimestamp(),
                'user' => $authCodeEntity->getUserIdentifier(),
                'scopes' => ScopeUtility::toString(...$authCodeEntity->getScopes()),
                'client' => $authCodeEntity->getClient()->getIdentifier(),
                'revoked' => 0,
            ])
            ->execute();
    }

    public function revokeAuthCode($codeId)
    {
        $this->logger->debug('Revoke auth code', ['identifier' => $codeId]);

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder
            ->update(self::TABLE)
            ->where(
                $queryBuilder->expr()->eq('identifier', $queryBuilder->createNamedParameter($codeId))
            )
            ->set('revoked', 1)
            ->execute();
    }

    public function isAuthCodeRevoked($codeId)
    {
        $row = $this->selectOneByIdentifier($codeId);
        if ($row) {
            return (bool) $row['revoked'];
        }

        return true;
    }
}
