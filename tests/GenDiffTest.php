<?php

use PHPUnit\Framework\TestCase;
use function Renakdup\Gendiff\generateAstDiff;

class GenDiffTest extends TestCase
{
    protected $fixtures = [];

    protected function setUp(): void
    {
        $this->fixtures['first'] = [
            'before' => file_get_contents(__DIR__ . '/fixtures/files/first/before.json'),
            'after' => file_get_contents(__DIR__ . '/fixtures/files/first/after.json'),
            'result' => require 'fixtures/files/first/result.php',
        ];

        $this->fixtures['second'] = [
            'before' => file_get_contents(__DIR__ . '/fixtures/files/second/before.json'),
            'after' => file_get_contents(__DIR__ . '/fixtures/files/second/after.json'),
            'result' => require 'fixtures/files/second/result.php',
        ];
    }

    public function testGenerateAstDiffFullFilled()
    {
        $diffResult = generateAstDiff($this->fixtures['first']['before'], $this->fixtures['first']['after']);

        $this->assertEquals($this->fixtures['first']['result'], $diffResult);
    }

    public function testGenerateAstDiffBeforeEmpty()
    {
        $diffResult = generateAstDiff($this->fixtures['second']['before'], $this->fixtures['second']['after']);

        $this->assertEquals($this->fixtures['second']['result'], $diffResult);
    }
}
