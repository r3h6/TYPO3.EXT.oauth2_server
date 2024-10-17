<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Domain\Model;

use League\OAuth2\Server\Entities\UserEntityInterface;

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

class User extends FrontendUser implements UserEntityInterface
{
    public function getIdentifier(): string
    {
        if ($this->uid === null) {
            throw new \RuntimeException('User has no uid', 1729194265611);
        }
        return (string)$this->uid;
    }
}
