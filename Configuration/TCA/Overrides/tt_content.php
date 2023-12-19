<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
defined('TYPO3') || die('Access denied.');

ExtensionUtility::registerPlugin(
    'Oauth2server',
    'Consent',
    'Oauth2: Consent',
    'EXT:oauth2_server/Resources/Public/Icons/Extension.svg'
);
