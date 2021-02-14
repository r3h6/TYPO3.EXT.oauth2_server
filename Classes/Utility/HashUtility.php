<?php

namespace R3H6\Oauth2Server\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;

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
