<?php

namespace R3H6\Oauth2Server\Domain\Repository;

use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use R3H6\Oauth2Server\Domain\Model\AuthCode;
use R3H6\Oauth2Server\Utility\ScopeUtility;

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
class AuthCodeRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    public function persist()
    {
        $this->persistenceManager->persistAll();
    }
}
