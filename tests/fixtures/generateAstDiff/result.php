<?php

return [
    'common' => [
        'operator' => '=',
        'children' => [
            'setting1' => [
                'operator' => '=',
                'value' => 'Value equal',
            ],
            'setting2' => [
                [
                    'operator' => '+',
                    'value' => 'Value new',
                ],
                [
                    'operator' => '-',
                    'value' => 'Value old',
                ],
            ],
            'setting3' => [
                [
                    'operator' => '+',
                    'value' => true,
                ],
                [
                    'operator' => '-',
                    'value' => false,
                ]
            ],
            'setting6' => [
                'operator' => '-',
                'value' => [
                    'some_key' => 'some_value'
                ]
            ],
            'setting10' => [
                'operator' => '=',
                'children' => [
                    'sub' => [
                        'operator' => '=',
                        'value' => [1, 2, 3],
                    ],
                    'sub2' => [
                        [
                            'operator' => '+',
                            'value' => [6, 7, 8]
                        ],
                        [
                            'operator' => '-',
                            'value' => [11, 22]
                        ]
                    ]
                ]
            ],
            'setting7' => [
                'operator' => '+',
                'value' => [
                    'some_key2' => 'some_value2'
                ]
            ],

        ]
    ],
    'operator' => [
        'operator' => '=',
        'value' => 'value'
    ]
];
