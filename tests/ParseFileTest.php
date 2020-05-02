<?php

namespace Renakdup\tests;

use PHPUnit\Framework\TestCase;

use function Renakdup\ParseFile\parseFile;
use function Renakdup\ParseFile\getFileType;

class ParseFileTest extends TestCase
{
    /**
     * Проверяем корретность определения extension по filepath
     *
     * @dataProvider typesProvider
     */
    public function testGetFileTypeBaseCase($a, $expected)
    {
        $this->assertEquals($expected, getFileType($a));
    }

    public function typesProvider()
    {
        return [
            ['index.json', 'json'],
            ['/public/fixtures/index.yaml', 'yaml'],
            ['', ''],
        ];
    }

    /**
     * Тестируем exception на несуществующий файл
     *
     * @throws \Exception
     */
    public function testNotExistFileException()
    {
        $this->expectException('Exception');
        parseFile(__DIR__ . '/not_exist_file.txt');
    }

    /**
     * Тестируем некорретный file extension
     *
     * @throws \Exception
     */
    public function testNotCorrectFileExtensionException()
    {
        $this->expectException('Exception');
        parseFile(__DIR__ . '/fixtures/file.not_correct_file_extension');
    }

    /**
     * Тестируем корректность парсинга json и yaml файлов
     *
     * @dataProvider filesProvider
     */
    public function testParseFiles($a, $expected)
    {
        $this->assertEquals($expected, parseFile($a));
    }

    public function filesProvider()
    {
        $fixtures['json'] = [
            'path' =>  __DIR__ . '/fixtures/parseFile/json/data.json',
            'result' => require 'fixtures/parseFile/json/result.php',
        ];

        $fixtures['yaml'] = [
            'path' => __DIR__ . '/fixtures/parseFile/yaml/data.yaml',
            'result' => require 'fixtures/parseFile/yaml/result.php',
        ];

        return $fixtures;
    }
}
