<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Domain\Repository;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use R3H6\Oauth2Server\Domain\Model\User;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\Repository;

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
 * @extends Repository<User>
 * @method ?\R3H6\Oauth2Server\Domain\Model\User findOneBy(array $criteria)
 */
class UserRepository extends Repository implements UserRepositoryInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function initializeObject(): void
    {
        /** \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings $querySettings */
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    public function getUserEntityByUserCredentials(string $username, string $password, string $grantType, ClientEntityInterface $clientEntity): UserEntityInterface
    {
        $this->logger->debug('Get user', ['username' => $username]);
        $user = $this->findOneBy(['username' => $username]);
        if ($user === null) {
            $this->logger->debug('No user found', ['username' => $username]);
            throw new \RuntimeException('Username or password invalid', 1607636289929);
        }

        $passwordHashFactory = GeneralUtility::makeInstance(PasswordHashFactory::class);
        $hashInstance = $passwordHashFactory->getDefaultHashInstance('FE');
        if (!$hashInstance->checkPassword($password, $user->getPassword())) {
            $this->logger->debug('Password check failed', ['username' => $username]);
            throw new \RuntimeException('Username or password invalid', 1607636289929);
        }

        return $user;
    }
}
