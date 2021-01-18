<?php
namespace R3H6\Oauth2Server\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

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
 * The repository for Clients
 */
class ClientRepository extends \TYPO3\CMS\Extbase\Persistence\Repository implements ClientRepositoryInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function initializeObject()
    {
        /** \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings $querySettings */
        $querySettings = $this->objectManager->get(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    public function getClientEntity($clientIdentifier)
    {
        $this->logger->debug('Get client', ['identifier' => $clientIdentifier]);
        $this->initializeObject();
        return $this->findOneByIdentifier($clientIdentifier);
    }


    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        $this->initializeObject();
        $client = $this->findOneByIdentifier($clientIdentifier);
        // $client = $this->__call('findByIdentifier', $clientIdentifier);

        // $query = $client->getQuery();
        // $queryParser = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser::class);
        // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($queryParser->convertQueryToDoctrineQueryBuilder($query)->getSQL());
        // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($queryParser->convertQueryToDoctrineQueryBuilder($query)->getParameters());

        // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($client);exit;
        if ($client === null) {
            return false;
        }
        if ($client->getGrantType() !== $grantType) {
            return false;
        }

        $passwordHashFactory = GeneralUtility::makeInstance(PasswordHashFactory::class);
        $hashInstance = $passwordHashFactory->getDefaultHashInstance(TYPO3_MODE);
        return $hashInstance->checkPassword($clientSecret, $client->getSecret());
    }
}
