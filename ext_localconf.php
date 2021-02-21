<?php

defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {
        // Hooks
        $GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = \R3H6\Oauth2Server\Hook\CreateClientSecretHook::class;

        // Plugins
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Oauth2Server',
            'Consent',
            [
                \R3H6\Oauth2Server\Controller\ConsentController::class => 'show',
            ],
            [
                \R3H6\Oauth2Server\Controller\ConsentController::class => 'show',
            ]
        );

        // Services
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
            'oauth2_server',
            'auth',
            \R3H6\Oauth2Server\Service\Oauth2AuthService::class,
            [
                'title' => 'Oauth2 authentication',
                'description' => 'Authenticate user by user uid and oauth access token.',
                'subtype' => 'getUserFE,authUserFE',
                'available' => true, // By default off
                'priority' => 99,
                'quality' => 50,
                'os' => '',
                'exec' => '',
                'className' => \R3H6\Oauth2Server\Service\Oauth2AuthService::class,
            ]
        );
    }
);
