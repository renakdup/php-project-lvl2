<?php

namespace Renakdup\tests;

use PHPUnit\Framework\TestCase;

use function Renakdup\GenerateAst\generateAstDiff;

class GenerateAstDiffTest extends TestCase
{
    protected $fixtures = [];

    protected function setUp(): void
    {
        $this->fixtures['first'] = [
            'before' =>  json_decode(file_get_contents(__DIR__ . '/fixtures/generateAstDiff/before.json')),
            'after' => json_decode(file_get_contents(__DIR__ . '/fixtures/generateAstDiff/after.json')),
            'result' => require 'fixtures/generateAstDiff/result.php',
        ];

        // TODO:: u can add one more fixtures
    }

    /**
     * Тестируем генерацию AST дерева
     */
    public function testGenerateAstDiff()
    {
        $diffResult = generateAstDiff($this->fixtures['first']['before'], $this->fixtures['first']['after']);
        $this->assertEquals($this->fixtures['first']['result'], $diffResult);
    }
}
