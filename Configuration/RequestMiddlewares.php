<?php

return [
    'frontend' => [
        'r3h6/oauth2-server/oauth2-handler' => [
            'target' => \R3H6\Oauth2Server\Middleware\AuthorizationHandler::class,
            'before' => [
                'typo3/cms-frontend/page-resolver',
            ],
            'after' => [
                'typo3/cms-frontend/static-route-resolver',
            ],
        ],
    ],
];
