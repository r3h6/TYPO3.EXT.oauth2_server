<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Domain\Repository;

use R3H6\Oauth2Server\Domain\Model\RefreshToken;
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
 * @extends Repository<RefreshToken>
 * @method ?\R3H6\Oauth2Server\Domain\Model\RefreshToken findOneBy(array $criteria)
 */
class RefreshTokenRepository extends Repository
{
    public function persist(): void
    {
        $this->persistenceManager->persistAll();
    }
}
