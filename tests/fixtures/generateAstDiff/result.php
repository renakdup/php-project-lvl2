<?php

return [
    'common' =>
        [
            'action' => 'equal',
            'children' =>
                [
                    'setting1' =>
                        [
                            'action' => 'equal',
                            'type' => 'simple',
                            'value' => 'Value equal',
                        ],
                    'setting2' =>
                        [
                            'diff' =>
                                [
                                    0 =>
                                        [
                                            'action' => 'add',
                                            'type' => 'simple',
                                            'value' => 'Value new',
                                        ],
                                    1 =>
                                        [
                                            'action' => 'remove',
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
                                            'action' => 'add',
                                            'type' => 'simple',
                                            'value' => true,
                                        ],
                                    1 =>
                                        [
                                            'action' => 'remove',
                                            'type' => 'simple',
                                            'value' => false,
                                        ],
                                ],
                        ],
                    'setting6' =>
                        [
                            'action' => 'remove',
                            'type' => 'object',
                            'value' => '{"some_key":"some_value"}',
                        ],
                    'setting10' =>
                        [
                            'action' => 'equal',
                            'children' =>
                                [
                                    'sub' =>
                                        [
                                            'action' => 'equal',
                                            'type' => 'array',
                                            'value' => '[1,2,3]',
                                        ],
                                    'sub2' =>
                                        [
                                            'diff' =>
                                                [
                                                    0 =>
                                                        [
                                                            'action' => 'add',
                                                            'type' => 'array',
                                                            'value' => '[6,7,8]',
                                                        ],
                                                    1 =>
                                                        [
                                                            'action' => 'remove',
                                                            'type' => 'array',
                                                            'value' => '[11,22]',
                                                        ],
                                                ],
                                        ],
                                ],
                        ],
                    'deep' =>
                        [
                            'action' => 'equal',
                            'children' =>
                                [
                                    'deep2' =>
                                        [
                                            'action' => 'equal',
                                            'children' =>
                                                [
                                                    'deep3' =>
                                                        [
                                                            'action' => 'equal',
                                                            'children' =>
                                                                [
                                                                    'deep4' =>
                                                                        [
                                                                            'diff' =>
                                                                                [
                                                                                    0 =>
                                                                                        [
                                                                                            'action' => 'add',
                                                                                            'type' => 'simple',
                                                                                            'value' => 'value-changed',
                                                                                        ],
                                                                                    1 =>
                                                                                        [
                                                                                            'action' => 'remove',
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
                                                                            'action' => 'add',
                                                                            'type' => 'array',
                                                                            'value' => '[3,5]',
                                                                        ],
                                                                    1 =>
                                                                        [
                                                                            'action' => 'remove',
                                                                            'type' => 'array',
                                                                            'value' => '["test","test2",3,5]',
                                                                        ],
                                                                ],
                                                        ],
                                                    'key-new2' =>
                                                        [
                                                            'action' => 'add',
                                                            'type' => 'simple',
                                                            'value' => 'test',
                                                        ],
                                                ],
                                        ],
                                ],
                        ],
                    'setting7' =>
                        [
                            'action' => 'add',
                            'type' => 'object',
                            'value' => '{"some_key2":"some_value2"}',
                        ],
                ],
        ],
    'action' =>
        [
            'action' => 'equal',
            'type' => 'simple',
            'value' => 'value',
        ],
];
