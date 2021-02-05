<?php
namespace R3H6\Oauth2Server\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

trait QueryBuilderAwareRepositoryTrait
{
    private function createQueryBuilder(): QueryBuilder
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(self::TABLE);
    }

    private function selectOneByIdentifier($identifier)
    {
        $queryBuilder = $this->createQueryBuilder();
        return $queryBuilder
            ->select('*')
            ->from(self::TABLE)
            ->where(
                $queryBuilder->expr()->eq('identifier', $queryBuilder->createNamedParameter($identifier))
            )
            ->execute()
            ->fetch();
    }
}
