<?php

return [
    'common' =>
        [
            'operator' => '=',
            'children' =>
                [
                    'setting1' =>
                        [
                            'operator' => '=',
                            'type' => 'simple',
                            'value' => 'Value equal',
                        ],
                    'setting2' =>
                        [
                            'diff' =>
                                [
                                    0 =>
                                        [
                                            'operator' => '+',
                                            'type' => 'simple',
                                            'value' => 'Value new',
                                        ],
                                    1 =>
                                        [
                                            'operator' => '-',
                                            'type' => 'simple',
                                            'value' => 'Value old',
                                        ],
                                ],
                        ],
                    'setting3' =>
                        [
                            'diff' =>
                                [
                                    0 =>
                                        [
                                            'operator' => '+',
                                            'type' => 'simple',
                                            'value' => true,
                                        ],
                                    1 =>
                                        [
                                            'operator' => '-',
                                            'type' => 'simple',
                                            'value' => false,
                                        ],
                                ],
                        ],
                    'setting6' =>
                        [
                            'operator' => '-',
                            'type' => 'object',
                            'value' => '{"some_key":"some_value"}',
                        ],
                    'setting10' =>
                        [
                            'operator' => '=',
                            'children' =>
                                [
                                    'sub' =>
                                        [
                                            'operator' => '=',
                                            'type' => 'array',
                                            'value' => '[1,2,3]',
                                        ],
                                    'sub2' =>
                                        [
                                            'diff' =>
                                                [
                                                    0 =>
                                                        [
                                                            'operator' => '+',
                                                            'type' => 'array',
                                                            'value' => '[6,7,8]',
                                                        ],
                                                    1 =>
                                                        [
                                                            'operator' => '-',
                                                            'type' => 'array',
                                                            'value' => '[11,22]',
                                                        ],
                                                ],
                                        ],
                                ],
                        ],
                    'deep' =>
                        [
                            'operator' => '=',
                            'children' =>
                                [
                                    'deep2' =>
                                        [
                                            'operator' => '=',
                                            'children' =>
                                                [
                                                    'deep3' =>
                                                        [
                                                            'operator' => '=',
                                                            'children' =>
                                                                [
                                                                    'deep4' =>
                                                                        [
                                                                            'diff' =>
                                                                                [
                                                                                    0 =>
                                                                                        [
                                                                                            'operator' => '+',
                                                                                            'type' => 'simple',
                                                                                            'value' => 'value-changed',
                                                                                        ],
                                                                                    1 =>
                                                                                        [
                                                                                            'operator' => '-',
                                                                                            'type' => 'simple',
                                                                                            'value' => 'value',
                                                                                        ],
                                                                                ],
                                                                        ],
                                                                ],
                                                        ],
                                                    'key-new' =>
                                                        [
                                                            'diff' =>
                                                                [
                                                                    0 =>
                                                                        [
                                                                            'operator' => '+',
                                                                            'type' => 'array',
                                                                            'value' => '[3,5]',
                                                                        ],
                                                                    1 =>
                                                                        [
                                                                            'operator' => '-',
                                                                            'type' => 'array',
                                                                            'value' => '["test","test2",3,5]',
                                                                        ],
                                                                ],
                                                        ],
                                                    'key-new2' =>
                                                        [
                                                            'operator' => '+',
                                                            'type' => 'simple',
                                                            'value' => 'test',
                                                        ],
                                                ],
                                        ],
                                ],
                        ],
                    'setting7' =>
                        [
                            'operator' => '+',
                            'type' => 'object',
                            'value' => '{"some_key2":"some_value2"}',
                        ],
                ],
        ],
    'operator' =>
        [
            'operator' => '=',
            'type' => 'simple',
            'value' => 'value',
        ],
];
