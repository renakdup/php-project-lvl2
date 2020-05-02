<?php

declare(strict_types=1);

namespace Renakdup\Gendiff;

use function Renakdup\RenderDiff\renderDiff;
use function Renakdup\GenerateAst\generateAstDiff;
use function Renakdup\ParseFile\parseFile;

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $data1 = parseFile($pathToFile1);
    $data2 = parseFile($pathToFile2);

    $astDiff = generateAstDiff($data1, $data2);
    $result = renderDiff($astDiff);

    return $result;
}
