<?php

declare(strict_types=1);

namespace Renakdup\tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

use function Renakdup\Gendiff\gendiff;

use const Renakdup\Gendiff\FORMAT_DEFAULT;
use const Renakdup\Gendiff\FORMAT_PLAIN;
use const Renakdup\Gendiff\FORMAT_JSON;

class GenDiffTest extends TestCase
{
    protected string $fileBefore;
    protected string $fileAfter;

    protected string $pathFixtures = __DIR__ . '/fixtures';

    protected function setUp(): void
    {
        $this->fileBefore = $this->pathFixtures . '/before.json';
        $this->fileAfter = $this->pathFixtures . '/after.json';
    }

    public function testDefaultRenderFromJson()
    {
        $result = file_get_contents($this->pathFixtures . '/default-result.txt');

        $this->assertEquals($result, genDiff($this->fileBefore, $this->fileAfter, FORMAT_DEFAULT));
    }

    public function testDefaultRenderFromYaml()
    {
        $result = file_get_contents($this->pathFixtures . '/default-result.txt');

        $this->assertEquals($result, genDiff($this->fileBefore, $this->fileAfter, FORMAT_DEFAULT));
    }

    public function testPlainRenderFromJson()
    {
        $result = file_get_contents($this->pathFixtures . '/plain-result.txt');

        $this->assertEquals($result, genDiff($this->fileBefore, $this->fileAfter, FORMAT_PLAIN));
    }

    public function testJsonRenderFromJson()
    {
        $result = file_get_contents($this->pathFixtures . '/json-result.json');

        $this->assertEquals($result, genDiff($this->fileBefore, $this->fileAfter, FORMAT_JSON));
    }
}
