<?php

declare(strict_types=1);

$EM_CONF[$_EXTKEY] = [
    'title' => 'Example Resources',
    'description' => 'Example resources for testing purposes.',
    'category' => 'fe',
    'author' => 'R3 H6',
    'author_email' => 'r3h6@outlook.com',
    'state' => 'beta',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.1.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
