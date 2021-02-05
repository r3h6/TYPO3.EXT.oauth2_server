<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
        ],
        'searchFields' => 'identifier,name',
        'iconfile' => 'EXT:oauth2_server/Resources/Public/Icons/tx_oauth2server_domain_model_client.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'identifier,name,secret,grant_type,redirect_uri,is_confidential',
    ],
    'types' => [
        '1' => ['showitem' => '
            identifier,
            name,
            secret,
            grant_type,
            redirect_uri,
            is_confidential
        '],
    ],
    'columns' => [

        'identifier' => [
            'exclude' => false,
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.identifier',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,alphanum_x,unique'
            ],
        ],
        'name' => [
            'exclude' => false,
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'secret' => [
            'exclude' => false,
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.secret',
            'config' => [
                'type' => 'passthrough'
            ],
        ],
        'grant_type' => [
            'exclude' => false,
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.grant_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['authorization_code', 'authorization_code'],
                    ['client_credentials', 'client_credentials'],
                    ['implicit', 'implicit'],
                    ['password', 'password'],
                    ['refresh_token', 'refresh_token'],
                ],
            ],
        ],
        'redirect_uri' => [
            'exclude' => false,
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.redirect_uri',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'eval' => 'trim'
            ]
        ],
        'is_confidential' => [
            'exclude' => false,
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.is_confidential',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
                'default' => 0,
            ]
        ],

    ],
];
