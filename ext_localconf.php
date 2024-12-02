<?php

defined('TYPO3') || die('Access denied.');

(static function () {
    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('scheduler')) {
        \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule(
            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\TYPO3\CMS\Scheduler\Task\TableGarbageCollectionTask::class]['options']['tables'],
            [
                'tx_oauth2server_domain_model_authcode' => [
                    'dateField' => 'expires_at',
                    'expirePeriod' => 1,
                ],
                'tx_oauth2server_domain_model_accesstoken' => [
                    'dateField' => 'expires_at',
                    'expirePeriod' => 1,
                ],
                'tx_oauth2server_domain_model_refreshtoken' => [
                    'dateField' => 'expires_at',
                    'expirePeriod' => 1,
                ],
            ]
        );
    }

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Oauth2Server',
        'Consent',
        [
            \R3H6\Oauth2Server\Controller\ConsentController::class => 'show',
        ],
        [
            \R3H6\Oauth2Server\Controller\ConsentController::class => 'show',
        ],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        'oauth2_server',
        'auth',
        \R3H6\Oauth2Server\Service\Oauth2AuthService::class,
        [
            'title' => 'Oauth2 authentication',
            'description' => 'Authenticate user by user uid and oauth access token.',
            'subtype' => 'getUserFE,authUserFE',
            'available' => true,
            'priority' => 99,
            'quality' => 50,
            'os' => '',
            'exec' => '',
            'className' => \R3H6\Oauth2Server\Service\Oauth2AuthService::class,
        ]
    );
})();
