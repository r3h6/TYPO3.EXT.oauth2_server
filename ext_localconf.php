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
    }
);
