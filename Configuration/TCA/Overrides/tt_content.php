<?php

defined('TYPO3') || die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Oauth2server',
    'Consent',
    'Oauth2: Consent',
    'EXT:oauth2_server/Resources/Public/Icons/Extension.svg'
);
