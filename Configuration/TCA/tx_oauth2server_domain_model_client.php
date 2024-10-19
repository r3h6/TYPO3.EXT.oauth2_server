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
            'endtime' => 'endtime',
        ],
        'searchFields' => 'identifier,name',
        'iconfile' => 'EXT:oauth2_server/Resources/Public/Icons/tx_oauth2server_domain_model_client.gif',
    ],
    'types' => [
        '1' => ['showitem' => '
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                identifier,
                secret,
                name,
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
                    ],
                ],
            ],
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
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
            ],
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
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
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
            ],
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
        ],
        'rowDescription' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.description',
            'config' => [
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            ],
        ],
        'identifier' => [
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.identifier',
            'config' => [
                'readOnly' => true,
                'type' => 'uuid',
                'size' => 30,
                'eval' => 'trim,alphanum_x,unique',
            ],
        ],
        'name' => [
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required',
            ],
        ],
        'secret' => [
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.secret',
            'config' => [
                // 'readOnly' => true,
                'type' => 'password',
                'fieldControl' => [
                    'passwordGenerator' => [
                        'renderType' => 'passwordGenerator',
                        // 'options' => [
                        //     'title' => 'Create random password',
                        //     'passwordRules' => [
                        //         'specialCharacters' => true,
                        //     ],
                        // ],
                    ],
                ],
            ],
        ],
        'grant_type' => [
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
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.redirect_uri',
            'config' => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 3,
                'eval' => 'trim',
            ],
        ],
        'is_confidential' => [
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.is_confidential',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
            ],
        ],
        'skip_consent' => [
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.skip_consent',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
            ],
        ],
        'allowed_scopes' => [
            'label' => 'LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.allowed_scopes',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectCheckBox',
                // 'itemsProcFunc' => \R3H6\Oauth2Server\Hook\AllowedScopesItemsProcFunc::class . '->addItems',
                'size' => 10,
                'items' => [
                    ['LLL:EXT:oauth2_server/Resources/Private/Language/locallang_db.xlf:tx_oauth2server_domain_model_client.allowed_scopes.items.0', '--div--'],
                ],
            ],
        ],

    ],
];
