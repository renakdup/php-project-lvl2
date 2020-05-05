<?php

declare(strict_types=1);

namespace Renakdup\test;

use PHPUnit\Framework\TestCase;

use function Renakdup\formatters\DefaultFormat\render;

class RenderDefaultTest extends TestCase
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
                'result' => file_get_contents(__DIR__ . '/fixtures/formatters/default-result.txt')
            ],
        ];
    }
}
