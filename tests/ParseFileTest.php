<?php

namespace Renakdup\tests;

use PHPUnit\Framework\TestCase;

use function Renakdup\ParseFile\parseFile;
use function Renakdup\ParseFile\getFileType;

class ParseFileTest extends TestCase
{
    /**
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

    public function testNotExistFileException()
    {
        $this->expectException('Exception');
        parseFile('not_exist_file.txt');
    }

    public function testNotCorrectFileExtensionException()
    {
        $this->expectException('Exception');
        parseFile(__DIR__ . '/fixtures/not_correct_file_extension.x1241');
    }

    /**
     * @dataProvider differentFilesProvider
     */
    public function testParseDifferentFiles($a, $expected)
    {
        $this->assertEquals($expected, parseFile($a));
    }

    public function differentFilesProvider()
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
