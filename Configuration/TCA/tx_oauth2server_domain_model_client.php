<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client',
        'label' => 'name',
        'label_alt' => 'identifier',
        'label_alt_force' => true,
        'descriptionColumn' => 'rowDescription',
        'editlock' => 'editlock',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime'
        ],
        'searchFields' => 'identifier,name',
        'iconfile' => 'EXT:oauth2_server/Resources/Public/Icons/tx_oauth2server_domain_model_client.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, row_description, identifier, name, secret, grant_type, redirect_uri, is_confidential, skip_consent, allowed_scopes',
    ],
    'types' => [
        '1' => ['showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                identifier,
                name,
                secret,
                grant_type,
                redirect_uri,
                is_confidential,
                skip_consent,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                --palette--;;hidden,
                --palette--;;access,
                allowed_scopes,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                rowDescription,
        '],
    ],
    'palettes' => [
        'hidden' => [
            'showitem' => '
                hidden;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:field.default.hidden
            ',
        ],
        'access' => [
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access',
            'showitem' => '
                starttime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel,
                endtime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel,
                --linebreak--,editlock
            ',
        ],
    ],
    'columns' => [
        'editlock' => [
            'exclude' => true,
            'displayCond' => 'HIDE_FOR_NON_ADMINS',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:editlock',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ]
                ],
            ]
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ]
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0
            ],
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly'
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ]
            ],
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly'
        ],
        'rowDescription' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.description',
            'config' => [
                'type' => 'text',
                'rows' => 5,
                'cols' => 30
            ]
        ],
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
                'renderType' => 'selectCheckBox',
                'items' => [
                    ['LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.grant_type.authorization_code', 'authorization_code'],
                    ['LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.grant_type.client_credentials', 'client_credentials'],
                    ['LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.grant_type.implicit', 'implicit'],
                    ['LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.grant_type.password', 'password'],
                    ['LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.grant_type.refresh_token', 'refresh_token'],
                ],
            ],
        ],
        'redirect_uri' => [
            'exclude' => false,
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.redirect_uri',
            'config' => [
                'type' => 'input',
                'size' => 30,
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
        'skip_consent' => [
            'exclude' => false,
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.skip_consent',
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
        'allowed_scopes' => [
            'exclude' => false,
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.allowed_scopes',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectCheckBox',
                'itemsProcFunc' => \R3H6\Oauth2Server\Hook\AllowedScopesItemsProcFunc::class . '->addItems',
                'size' => 10,
                'items' => [
                    ['LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.allowed_scopes.items.0', '--div--']
                ]
            ]
        ],

    ],
];
