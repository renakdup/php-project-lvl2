<?php

namespace Renakdup\tests;

use PHPUnit\Framework\TestCase;

use function Renakdup\Gendiff\genDiff;

use const Renakdup\inc\CommandLine\FORMAT_JSON;
use const Renakdup\inc\CommandLine\FORMAT_PLAIN;

class GenDiffTest extends TestCase
{
    /**
     * Тестируем корретность сравнения файлов разных форматов
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
                'before' => __DIR__ . '/fixtures/generateAstDiff/before.json',
                'after'  => __DIR__ . '/fixtures/generateAstDiff/after.json',
                'type'   => FORMAT_JSON,
                'result' => file_get_contents(__DIR__ . '/fixtures/formatters/json-result.txt'),
            ],
            [
                'before' => __DIR__ . '/fixtures/generateAstDiff/before.json',
                'after'  => __DIR__ . '/fixtures/generateAstDiff/after.json',
                'type'   => FORMAT_PLAIN,
                'result' => file_get_contents(__DIR__ . '/fixtures/formatters/plain-result.txt'),
            ]
        ];
    }

    /**
     * Тестируем выброс Exception для некорретного формата отображения данных
     *
     * @throws Exception
     */
    public function testCorrectTypeException()
    {
        $before = __DIR__ . '/fixtures/generateAstDiff/before.json';
        $after  = __DIR__ . '/fixtures/generateAstDiff/after.json';
        $type = 'not_correct_type';

        $this->expectException('Exception');
        genDiff($before, $after, $type);
    }
}
