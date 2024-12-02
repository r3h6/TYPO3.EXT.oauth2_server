<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Utility;

use League\OAuth2\Server\Entities\ScopeEntityInterface;

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

final class ScopeUtility
{
    public static function toString(ScopeEntityInterface ...$scopes): string
    {
        return implode(', ', static::toStrings(...$scopes));
    }

    /**
     * @return string[]
     */
    public static function toStrings(ScopeEntityInterface ...$scopes): array
    {
        return array_map(function (ScopeEntityInterface $scope) {
            return $scope->getIdentifier();
        }, $scopes);
    }
}
