<?php

use R3H6\Oauth2Server\Hook\CreateClientSecretHook;

defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {
        $GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = \R3H6\Oauth2Server\Hook\CreateClientSecretHook::class;
    }
);
