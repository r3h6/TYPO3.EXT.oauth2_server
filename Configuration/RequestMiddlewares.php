<?php

return [
    'frontend' => [

        'r3h6/oauth2-server/initializer' => [
            'target' => \R3H6\Oauth2Server\Middleware\Initializer::class,
            'after' => [
                'typo3/cms-frontend/site',
                'typo3/cms-core/request-token-middleware',
            ],
            'before' => [
                'typo3/cms-frontend/authentication',
            ],
        ],
        'r3h6/oauth2-server/dispatcher' => [
            'target' => \R3H6\Oauth2Server\Middleware\Dispatcher::class,
            'after' => [
                'r3h6/oauth2-server/initializer',
                'typo3/cms-frontend/authentication',
            ],
            'before' => [
                'typo3/cms-frontend/base-redirect-resolver',
            ],
        ],
    ],
];
