<?php

namespace R3H6\Oauth2Server\Utility;

use League\OAuth2\Server\Entities\ScopeEntityInterface;

final class ScopeUtility
{
    public static function toString(ScopeEntityInterface ...$scopes): string
    {
        return implode(', ', array_map(function(ScopeEntityInterface $scope) {
            return $scope->getIdentifier();
        }, $scopes));
    }
}
