<?php

declare(strict_types=1);

$EM_CONF[$_EXTKEY] = [
    'title' => 'OAuth2 Server',
    'description' => 'OAuth2 server for TYPO3 based on PHP League\'s OAuth2 Server.',
    'category' => 'fe',
    'author' => 'R3 H6',
    'author_email' => 'r3h6@outlook.com',
    'state' => 'beta',
    'version' => '2.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.4.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'scheduler' => '12.4.0-13.4.99',
        ],
    ],
];
