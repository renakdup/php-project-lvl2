<?php

use PHPUnit\Framework\TestCase;
use function Renakdup\GenerateAst\generateAstDiff;

class GenerateAstDiffTest extends TestCase
{
    protected $fixtures = [];

    protected function setUp(): void
    {
        $this->fixtures['first'] = [
            'before' => require 'fixtures/json/first/before-parsed.php',
            'after' => require 'fixtures/json/first/after-parsed.php',
            'result' => require 'fixtures/json/first/result.php',
        ];

        // TODO:: u can add one more fixtures
    }

    public function testGenerateAstDiff()
    {
        $diffResult = generateAstDiff($this->fixtures['first']['before'], $this->fixtures['first']['after']);

        $this->assertEquals($this->fixtures['first']['result'], $diffResult);
    }
}
