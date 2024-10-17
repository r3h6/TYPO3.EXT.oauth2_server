<?php

$config = \TYPO3\CodingStandards\CsFixerConfig::create();
$config->addRules([
    'declare_strict_types' => true,
]);
$config->getFinder()->in(__DIR__)->exclude([
    '.Build',
    'config/system',
]);

return $config;
