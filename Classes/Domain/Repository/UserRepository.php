<?php
namespace R3H6\Oauth2Server\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Configuration\TsConfigParser;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;

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
        $this->initializeObject();
        $user = $this->findOneByUsername($username);
        if ($user === null) {
            throw new \RuntimeException('Username or password invalid', 1607636289929);
        }

        $passwordHashFactory = GeneralUtility::makeInstance(PasswordHashFactory::class);
        $hashInstance = $passwordHashFactory->getDefaultHashInstance(TYPO3_MODE);
        if (!$hashInstance->checkPassword($password, $user->getPassword())) {
            throw new \RuntimeException('Username or password invalid', 1607636289929);
        }

        return $user;
    }
}
