<?php

use R3H6\Oauth2Server\Middleware\Oauth2Configuration;
use R3H6\Oauth2Server\Middleware\Oauth2Routing;
use R3H6\Oauth2Server\Middleware\Oauth2Authenticator;
use R3H6\Oauth2Server\Middleware\Oauth2Firewall;
use R3H6\Oauth2Server\Middleware\Oauth2Dispatcher;
return [
    'frontend' => [
        'r3h6/oauth2-server/configuration' => [
            'target' => Oauth2Configuration::class,
            'after' => [
                'typo3/cms-frontend/site',
            ],
            'before' => [
                'typo3/cms-frontend/authentication',
            ],
        ],
        'r3h6/oauth2-server/routing' => [
            'target' => Oauth2Routing::class,
            'after' => [
                'r3h6/oauth2-server/configuration',
            ],
            'before' => [
                'typo3/cms-frontend/authentication',
            ],
        ],
        'r3h6/oauth2-server/authentication' => [
            'target' => Oauth2Authenticator::class,
            'after' => [
                'r3h6/oauth2-server/routing',
            ],
            'before' => [
                'typo3/cms-frontend/authentication',
            ],
        ],
        'r3h6/oauth2-server/firewall' => [
            'target' => Oauth2Firewall::class,
            'after' => [
                'typo3/cms-frontend/authentication',
            ],
            'before' => [
                'typo3/cms-frontend/base-redirect-resolver',
            ],
        ],
        'r3h6/oauth2-server/dispatcher' => [
            'target' => Oauth2Dispatcher::class,
            'after' => [
                'r3h6/oauth2-server/firewall',
            ],
            'before' => [
                'typo3/cms-frontend/base-redirect-resolver',
            ],
        ],
    ],
];
