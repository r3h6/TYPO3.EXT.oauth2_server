<?php

$config = \TYPO3\CodingStandards\CsFixerConfig::create();
$config->getFinder()->in(__DIR__)->exclude([
    '.Build',
    'config/system',
]);

// $config->addRules([
//     'declare_strict_types' => true,
// ]);

return $config;
