<?php
namespace R3H6\Oauth2Server\Domain\Repository;

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
class AuthCodeRepository extends \TYPO3\CMS\Extbase\Persistence\Repository implements AuthCodeRepositoryInterface
{

    public function getNewAuthCode()
    {
        return new AuthCode();
    }

    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity) { }

    public function revokeAuthCode($codeId) { }

    public function isAuthCodeRevoked($codeId) { }
}
