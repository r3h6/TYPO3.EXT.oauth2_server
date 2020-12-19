<?php
declare(strict_types = 1);

return [
    \R3H6\Oauth2Server\Domain\Model\User::class => [
        'tableName' => 'fe_users',
        'properties' => [
            'identifier' => [
                'fieldName' => 'uid'
            ],
        ],
    ],
];
