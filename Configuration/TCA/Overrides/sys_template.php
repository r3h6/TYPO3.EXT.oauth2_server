<?php

declare(strict_types=1);

defined('TYPO3') || die('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('oauth2_server', 'Configuration/TypoScript', 'OAuth2 Server');
