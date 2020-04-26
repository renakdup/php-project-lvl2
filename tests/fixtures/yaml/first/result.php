<?php

return [
    'host' => [
        'operator' => '=',
        'value' => 'hexlet.io',
    ],
    'timeout' => [
        [
            'operator' => '+',
            'value' => 20,
        ],
        [
            'operator' => '-',
            'value' => 50,
        ]
    ],
    'proxy' => [
        'operator' => '-',
        'value' => '123.234.53.22'
    ],
    'verbose' => [
        'operator' => '+',
        'value' => true
    ]
];