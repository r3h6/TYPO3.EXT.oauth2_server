<?php
declare(strict_types = 1);

use R3H6\Oauth2Server\Domain\Model\User;
use R3H6\Oauth2Server\Domain\Model\UserGroup;

return [
    User::class => [
        'tableName' => 'fe_users',
        'properties' => [
            'identifier' => [
                'fieldName' => 'uid'
            ],
        ],
    ],
    UserGroup::class => [
        'tableName' => 'fe_groups',
    ],
];
