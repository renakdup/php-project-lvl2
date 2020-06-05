<?php

declare(strict_types=1);

namespace CalcDiff\tests;

use PHPUnit\Framework\TestCase;

use function CalcDiff\Gendiff\gendiff;

use const CalcDiff\Gendiff\FORMAT_TREE;
use const CalcDiff\Gendiff\FORMAT_PLAIN;
use const CalcDiff\Gendiff\FORMAT_JSON;

class GenDiffTest extends TestCase
{
    protected string $pathFixtures = __DIR__ . '/fixtures';

    /**
     * @dataProvider renderDataProvider
     * @throws \Exception
     */
    public function testDefaultRenderFromJson($before, $after, $result, $format)
    {
        $result = file_get_contents($result);

        $this->assertEquals($result, genDiff($before, $after, $format));
    }

    public function renderDataProvider()
    {
        return [
            [
                $this->pathFixtures . '/before.json',
                $this->pathFixtures . '/after.json',
                $this->pathFixtures . '/tree-result.txt',
                FORMAT_TREE,
            ],
            [
                $this->pathFixtures . '/before.yaml',
                $this->pathFixtures . '/after.yaml',
                $this->pathFixtures . '/tree-result.txt',
                FORMAT_TREE,
            ],
            [
                $this->pathFixtures . '/before.json',
                $this->pathFixtures . '/after.json',
                $this->pathFixtures . '/plain-result.txt',
                FORMAT_PLAIN,
            ],
            [
                $this->pathFixtures . '/before.json',
                $this->pathFixtures . '/after.json',
                $this->pathFixtures . '/json-result.json',
                FORMAT_JSON,
            ],
        ];
    }
}
