<?php

declare(strict_types=1);

return [
    \R3H6\Oauth2Server\Domain\Model\User::class => [
        'tableName' => 'fe_users',
    ],
    \R3H6\Oauth2Server\Domain\Model\FrontendUserGroup::class => [
        'tableName' => 'fe_groups',
    ],
];
