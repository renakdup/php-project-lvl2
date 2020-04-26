<?php

use PHPUnit\Framework\TestCase;
use function Renakdup\ParseFile\parseFile;
use function Renakdup\ParseFile\getFileType;

class ParseFileTest extends TestCase
{
    /**
     * @dataProvider typesProvider
     */
    public function testGetFileType($a, $expected)
    {
        $this->assertEquals($expected, getFileType($a));
    }

    public function typesProvider()
    {
        return [
            ['index.json', 'json'],
            ['/public/fixtures/index.yaml', 'yaml']
        ];
    }
}
