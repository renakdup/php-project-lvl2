<?php

declare(strict_types=1);

namespace Renakdup\Gendiff;

use function Renakdup\formatters\RenderJson\render as jsonRender;
use function Renakdup\formatters\RenderPlain\render as plainRender;
use function Renakdup\GenerateAst\generateAstDiff;
use function Renakdup\ParseFile\parseFile;

use const Renakdup\inc\CommandLine\FORMAT_JSON;
use const Renakdup\inc\CommandLine\FORMAT_PLAIN;

function genDiff(string $pathToFile1, string $pathToFile2, string $format): string
{
    $data1 = parseFile($pathToFile1);
    $data2 = parseFile($pathToFile2);

    $astDiff = generateAstDiff($data1, $data2);

    switch ($format) {
        case FORMAT_JSON:
            $result = jsonRender($astDiff);
            break;
        case FORMAT_PLAIN:
            $result = plainRender($astDiff);
            break;
        default:
            throw new \Exception("Format '{$format}' isn't correct");
    }

    return $result;
}
