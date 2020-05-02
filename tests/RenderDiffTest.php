<?php

namespace Renakdup\test;

use PHPUnit\Framework\TestCase;

use function Renakdup\RenderDiff\renderDiff;

class RenderDiffTest extends TestCase
{
    /**
     * @dataProvider dataOutputDiffProvider
     */
    public function testOutputDiff($a, $expected)
    {
        $this->assertEquals($expected, renderDiff($a));
    }

    public function dataOutputDiffProvider()
    {
        return [
            [
                'astDiff' => require 'fixtures/generateAstDiff/result.php',
                'result' => file_get_contents(__DIR__ . '/fixtures/renderDiff/result.txt')
            ],
        ];
    }
}
