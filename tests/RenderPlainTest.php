<?php

declare(strict_types=1);

namespace Renakdup\test;

use PHPUnit\Framework\TestCase;

use function Renakdup\formatters\Plain\render;

class RenderPlainTest extends TestCase
{
    /**
     * @dataProvider dataOutputDiffProvider
     */
    public function testOutputDiff($a, $expected)
    {
        $this->assertEquals($expected, render($a));
    }

    public function dataOutputDiffProvider()
    {
        return [
            [
                'astDiff' => require 'fixtures/generateAstDiff/result.php',
                'result' => file_get_contents(__DIR__ . '/fixtures/formatters/plain-result.txt')
            ],
        ];
    }
}
