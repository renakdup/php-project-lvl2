<?php

namespace Renakdup\tests;

use PHPUnit\Framework\TestCase;

use function Renakdup\Gendiff\genDiff;
use function Renakdup\ParseFile\parseFile;

use const Renakdup\inc\CommandLine\FORMAT_DEFAULT;
use const Renakdup\inc\CommandLine\FORMAT_PLAIN;
use const Renakdup\inc\CommandLine\FORMAT_JSON;

class GenDiffTest extends TestCase
{
    /**
     * Тестируем сравнение файлов yaml, json и полученный diff типов (plain, json, default)
     *
     * @dataProvider inputOutputGendiffDataProvider
     */
    public function testGendiff($before, $after, $type, $expected)
    {
        $this->assertEquals($expected, genDiff($before, $after, $type));
    }

    public function inputOutputGendiffDataProvider()
    {
        return [
            [
                'before' => parseFile(__DIR__ . '/fixtures/before.json'),
                'after'  => parseFile(__DIR__ . '/fixtures/after.json'),
                'type'   => FORMAT_DEFAULT,
                'result' => file_get_contents(__DIR__ . '/fixtures/default-result.txt'),
            ],
            [
                'before' => parseFile(__DIR__ . '/fixtures/before.yaml'),
                'after'  => parseFile(__DIR__ . '/fixtures/after.yaml'),
                'type'   => FORMAT_DEFAULT,
                'result' => file_get_contents(__DIR__ . '/fixtures/default-result.txt'),
            ],
            [
                'before' => parseFile(__DIR__ . '/fixtures/before.json'),
                'after'  => parseFile(__DIR__ . '/fixtures/after.json'),
                'type'   => FORMAT_PLAIN,
                'result' => file_get_contents(__DIR__ . '/fixtures/plain-result.txt'),
            ],
            [
                'before' => parseFile(__DIR__ . '/fixtures/before.json'),
                'after'  => parseFile(__DIR__ . '/fixtures/after.json'),
                'type'   => FORMAT_JSON,
                'result' => file_get_contents(__DIR__ . '/fixtures/json-result.json'),
            ]
        ];
    }
}
