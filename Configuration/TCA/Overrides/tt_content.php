<?php

defined('TYPO3') || die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Oauth2Server',
    'Consent',
    'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_be.xlf:plugin.oauth2server_consent.title',
    'mimetypes-x-content-consent',
    'forms'
);
