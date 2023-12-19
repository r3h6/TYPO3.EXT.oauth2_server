<?php
declare(strict_types = 1);

use R3H6\Oauth2Server\Domain\Model\User;

return [
    User::class => [
        'tableName' => 'fe_users',
        'properties' => [
            'identifier' => [
                'fieldName' => 'uid'
            ],
        ],
    ],
];
