<?php

namespace R3H6\Oauth2Server\Utility;

use League\OAuth2\Server\Entities\ScopeEntityInterface;

final class ScopeUtility
{
    public static function toString(ScopeEntityInterface ...$scopes): string
    {
        return implode(', ', static::toStrings(...$scopes));
    }

    public static function toStrings(ScopeEntityInterface ...$scopes): array
    {
        return array_map(function (ScopeEntityInterface $scope) {
            return $scope->getIdentifier();
        }, $scopes);
    }
}
