<?php

declare(strict_types=1);

namespace Renakdup\tests;

use PHPUnit\Framework\TestCase;

use function Renakdup\ParseFile\parseFile;
use function Renakdup\ParseFile\getFileType;

class ParseFileTest extends TestCase
{
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
