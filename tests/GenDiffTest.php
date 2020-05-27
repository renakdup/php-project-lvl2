<?php

declare(strict_types=1);

namespace CalcDiff\tests;

use PHPUnit\Framework\TestCase;

use function CalcDiff\Gendiff\gendiff;

use const CalcDiff\Gendiff\FORMAT_DEFAULT;
use const CalcDiff\Gendiff\FORMAT_PLAIN;
use const CalcDiff\Gendiff\FORMAT_JSON;

class GenDiffTest extends TestCase
{
    protected string $valueBeforeFilePath;
    protected string $valueAfterFilePath;

    protected string $pathFixtures = __DIR__ . '/fixtures';

    protected function setUp(): void
    {
        $this->valueBeforeFilePath = $this->pathFixtures . '/before.json';
        $this->valueAfterFilePath = $this->pathFixtures . '/after.json';
    }

    public function testDefaultRenderFromJson()
    {
        $result = file_get_contents($this->pathFixtures . '/default-result.txt');

        $this->assertEquals($result, genDiff($this->valueBeforeFilePath, $this->valueAfterFilePath, FORMAT_DEFAULT));
    }

    public function testDefaultRenderFromYaml()
    {
        $result = file_get_contents($this->pathFixtures . '/default-result.txt');

        $this->assertEquals($result, genDiff($this->valueBeforeFilePath, $this->valueAfterFilePath, FORMAT_DEFAULT));
    }

    public function testPlainRenderFromJson()
    {
        $result = file_get_contents($this->pathFixtures . '/plain-result.txt');

        $this->assertEquals($result, genDiff($this->valueBeforeFilePath, $this->valueAfterFilePath, FORMAT_PLAIN));
    }

    public function testJsonRenderFromJson()
    {
        $result = file_get_contents($this->pathFixtures . '/json-result.json');

        $this->assertEquals($result, genDiff($this->valueBeforeFilePath, $this->valueAfterFilePath, FORMAT_JSON));
    }
}
