<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;

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
 * HashUtility
 */
final class HashUtility
{
    public static function encode(string $raw): string
    {
        return static::getHashService()->appendHmac(base64_encode($raw));
    }

    public static function decode(string $hash): string
    {
        return base64_decode(static::getHashService()->validateAndStripHmac($hash));
    }

    private static function getHashService(): HashService
    {
        return GeneralUtility::makeInstance(HashService::class);
    }
}
