<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Domain\Repository;

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
 * The repository for RefreshTokens
 */
class RefreshTokenRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    public function persist()
    {
        $this->persistenceManager->persistAll();
    }
}
