<?php

declare(strict_types=1);

defined('TYPO3') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'ExampleResources',
    'Api',
    [\R3H6\ExampleResources\Controller\ExtbaseController::class => 'index'],
    [\R3H6\ExampleResources\Controller\ExtbaseController::class => 'index'],
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
