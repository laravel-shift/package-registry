<?php

return [
    'latest' => [
        'laravel/nova' => ['type' => 'require', 'constraint' => '^3.15'],
        'laravel/spark-aurelius' => ['type' => 'require', 'constraint' => '~10.0'],
    ],
    '8.x' => [
        'laravel/nova' => ['type' => 'require', 'constraints' => ['earliest' => '^3.10', 'latest' => '^3.15']],
        'laravel/spark-aurelius' => ['type' => 'require', 'constraints' => ['earliest' => '~10.0', 'latest' => '~10.0']],
    ],
    '7.x' => [
        'laravel/nova' => ['type' => 'require', 'constraints' => ['earliest' => '^3.0', 'latest' => '^3.0']],
        'laravel/spark-aurelius' => ['type' => 'require', 'constraints' => ['earliest' => '~9.0', 'latest' => '^3.0']],
    ],
    '6.x' => [
        'laravel/nova' => ['type' => 'require', 'constraints' => ['earliest' => '^2.3', 'latest' => '^2.3']],
        'laravel/spark-aurelius' => ['type' => 'require', 'constraints' => ['earliest' => '~9.0', 'latest' => '~10.0']],
    ],
];
