<?php

namespace R3H6\Oauth2Server\Domain\Repository;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerAwareInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use R3H6\Oauth2Server\Utility\ScopeUtility;
use R3H6\Oauth2Server\Domain\Model\AccessToken;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
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
class AccessTokenRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    public function persist()
    {
        $this->persistenceManager->persistAll();
    }

    /**
     * @param string|int $userId
     * @param string|int $clientId
     * @param string[] $scopes
     */
    public function hasValidAccessToken($userId, $clientId, array $scopes)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd([
                $query->equals('user', $userId),
                $query->equals('client', $clientId),
                $query->equals('revoked', false),
            ])
        );
        $query->setOrderings([
            'expiresAt' => QueryInterface::ORDER_DESCENDING,
        ]);
        $query->setLimit(1);

        $token = $query->execute()->getFirst();

        return $token !== null && array_diff(GeneralUtility::trimExplode(',', $token->getScopes()), $scopes) === [];
    }
}
