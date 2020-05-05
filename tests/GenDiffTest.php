<?php

namespace Renakdup\tests;

use PHPUnit\Framework\TestCase;

use function Renakdup\Gendiff\genDiff;
use function Renakdup\ParseFile\parseFile;

use const Renakdup\inc\CommandLine\FORMAT_DEFAULT;
use const Renakdup\inc\CommandLine\FORMAT_PLAIN;

class GenDiffTest extends TestCase
{
    /**
     * Тестируем сравнение файлов и полученный diff, разных типов вывода
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
                'before' => parseFile(__DIR__ . '/fixtures/generateAstDiff/before.json'),
                'after'  => parseFile(__DIR__ . '/fixtures/generateAstDiff/after.json'),
                'type'   => FORMAT_DEFAULT,
                'result' => file_get_contents(__DIR__ . '/fixtures/formatters/default-result.txt'),
            ],
            [
                'before' => parseFile(__DIR__ . '/fixtures/generateAstDiff/before.json'),
                'after'  => parseFile(__DIR__ . '/fixtures/generateAstDiff/after.json'),
                'type'   => FORMAT_PLAIN,
                'result' => file_get_contents(__DIR__ . '/fixtures/formatters/plain-result.txt'),
            ]
        ];
    }

    /**
     * Тестируем выброс Exception для некорретного формата отображения данных
     */
    public function testCorrectTypeException()
    {
        $before = parseFile(__DIR__ . '/fixtures/generateAstDiff/before.json');
        $after  = parseFile(__DIR__ . '/fixtures/generateAstDiff/after.json');
        $type = 'not_correct_type';

        $this->expectException('Exception');
        genDiff($before, $after, $type);
    }
}
