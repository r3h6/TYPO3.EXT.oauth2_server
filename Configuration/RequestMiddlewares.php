<?php

return [
    'frontend' => [
        'r3h6/oauth2-server/oauth2-handler' => [
            'target' => \R3H6\Oauth2Server\Middleware\AuthorizationHandler::class,
            'before' => [
                'typo3/cms-frontend/base-redirect-resolver',
            ],
            'after' => [
                'typo3/cms-frontend/authentication',
            ],
        ],
    ],
];
