<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client',
        'label' => 'uid',
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
            'label' => 'identifier',
            'config' => [
                'readOnly' => true,
                'type' => 'input',
                'size' => '30',
                'eval' => 'trim,alphanum_x,unique',
            ],
        ],
        'name' => [
            'label' => 'name',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'eval' => 'trim',
            ],
        ],
        'secret' => [
            'label' => 'secret',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'grant_type' => [
            'label' => 'grant_type',
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
            'label' => 'redirect_uri',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'eval' => 'trim',
            ],
        ],
        'is_confidential' => [
            'label' => 'is_confidential',
            'config' => [
                'type' => 'check',
            ],
        ],
    ],
];
