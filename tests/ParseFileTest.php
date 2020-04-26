<?php

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
}
