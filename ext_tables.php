<?php
defined('TYPO3') || die('Access denied.');

call_user_func(
    function()
    {

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('oauth2_server', 'Configuration/TypoScript', 'OAuth2 Server');

    }
);
