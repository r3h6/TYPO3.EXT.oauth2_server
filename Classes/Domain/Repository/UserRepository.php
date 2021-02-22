<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Domain\Repository;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
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
 * The repository for Users
 */
class UserRepository extends \TYPO3\CMS\Extbase\Persistence\Repository implements UserRepositoryInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function initializeObject()
    {
        /** \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings $querySettings */
        $querySettings = $this->objectManager->get(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        $this->logger->debug('Get user', ['username' => $username]);
        $user = $this->findOneByUsername($username);
        if ($user === null) {
            $this->logger->debug('No user found', ['username' => $username]);
            throw new \RuntimeException('Username or password invalid', 1607636289929);
        }

        $passwordHashFactory = GeneralUtility::makeInstance(PasswordHashFactory::class);
        $hashInstance = $passwordHashFactory->getDefaultHashInstance(TYPO3_MODE);
        if (!$hashInstance->checkPassword($password, $user->getPassword())) {
            $this->logger->debug('Password check failed', ['username' => $username]);
            throw new \RuntimeException('Username or password invalid', 1607636289929);
        }

        return $user;
    }
}
