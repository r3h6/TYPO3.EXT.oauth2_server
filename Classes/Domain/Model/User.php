<?php

namespace R3H6\Oauth2Server\Domain\Model;

use League\OAuth2\Server\Entities\UserEntityInterface;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;

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
 * User
 */
final class User extends FrontendUser implements UserEntityInterface
{
    public function getIdentifier()
    {
        return $this->uid;
    }
}
