<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

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
 * @extends \TYPO3\CMS\Extbase\Persistence\Repository<\R3H6\Oauth2Server\Domain\Model\AccessToken>
 * @method ?\R3H6\Oauth2Server\Domain\Model\AccessToken findOneBy(array $criteria)
 */
class AccessTokenRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    public function persist(): void
    {
        $this->persistenceManager->persistAll();
    }

    /**
     * @param string|int $userId
     * @param string|int $clientId
     * @param string[] $scopes
     */
    public function hasValidAccessToken($userId, $clientId, array $scopes): bool
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('user', $userId),
                $query->equals('client', $clientId),
                $query->equals('revoked', false),
            )
        );
        $query->setOrderings([
            'expiresAt' => QueryInterface::ORDER_DESCENDING,
        ]);
        $query->setLimit(1);

        $token = $query->execute()->getFirst();

        return $token !== null && array_diff(GeneralUtility::trimExplode(',', $token->getScopes()), $scopes) === [];
    }
}
