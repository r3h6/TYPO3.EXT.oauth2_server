<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_accesstoken',
        'label' => 'identifier',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'enablecolumns' => [
            'endtime' => 'expires_at',
        ],
        'rootLevel' => 1,
        'hideTable' => true,
        'searchFields' => 'identifier',
        'iconfile' => 'EXT:oauth2_server/Resources/Public/Icons/tx_oauth2server_domain_model_accesstoken.gif',
    ],
    'types' => [
        '1' => ['showitem' => 'identifier, expires_at, user, scopes, client, revoked'],
    ],
    'columns' => [

        'identifier' => [
            'exclude' => false,
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_accesstoken.identifier',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'expires_at' => [
            'exclude' => false,
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_accesstoken.expires_at',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'eval' => 'datetime',
                'default' => time(),
            ],
        ],
        'user' => [
            'exclude' => false,
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_accesstoken.user',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'scopes' => [
            'exclude' => false,
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_accesstoken.scopes',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
        ],
        'client' => [
            'exclude' => false,
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_accesstoken.client',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'revoked' => [
            'exclude' => false,
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_accesstoken.revoked',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
            ],
        ],

    ],
];
