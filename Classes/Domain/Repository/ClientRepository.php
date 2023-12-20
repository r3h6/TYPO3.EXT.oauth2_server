<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Core\Information\Typo3Version;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

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
class ClientRepository extends Repository implements ClientRepositoryInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function initializeObject()
    {
        /** \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings $querySettings */
        $querySettings = version_compare(GeneralUtility::makeInstance(Typo3Version::class)->getVersion(), '11.5', '>=') ?
            GeneralUtility::makeInstance(Typo3QuerySettings::class):
            $this->objectManager->get(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    public function getClientEntity($clientIdentifier)
    {
        $this->logger->debug('Get client', ['identifier' => $clientIdentifier]);
        return $this->findOneByIdentifier($clientIdentifier);
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        $client = $this->findOneByIdentifier($clientIdentifier);

        if ($client === null) {
            $this->logger->debug('No client found', ['identifier' => $clientIdentifier]);
            return false;
        }
        if (GeneralUtility::inList($client->getGrantType(), $grantType) === false) {
            $this->logger->debug('Grant type not allowed by client', ['identifier' => $clientIdentifier, 'grantType' => $grantType]);
            return false;
        }

        $passwordHashFactory = GeneralUtility::makeInstance(PasswordHashFactory::class);
        $hashInstance = $passwordHashFactory->getDefaultHashInstance('FE');
        return $hashInstance->checkPassword($clientSecret, $client->getSecret());
    }
}
