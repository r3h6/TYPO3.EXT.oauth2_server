<?php

declare(strict_types=1);

defined('TYPO3') || die('Access denied.');

(static function () {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Oauth2Server',
        'Consent',
        'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_be.xlf:plugin.oauth2server_consent.title',
        'mimetypes-x-content-consent',
        'forms'
    );
})();
