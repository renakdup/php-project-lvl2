<?php

namespace Renakdup\Gendiff;

use function Renakdup\formatters\DefaultFormat\render as defaultRender;
use function Renakdup\formatters\Json\render as jsonRender;
use function Renakdup\formatters\Plain\render as plainRender;
use function Renakdup\GenerateAst\generateAstDiff;
use function Renakdup\ParseFile\parseFile;

function gendiff(string $pathFileBefore, string $pathFileAfter, string $format): string
{
    $contentBefore = parseFile($pathFileBefore);
    $contentAfter = parseFile($pathFileAfter);

    $astDiff = generateAstDiff($contentBefore, $contentAfter);

    switch ($format) {
        case FORMAT_PLAIN:
            return plainRender($astDiff);
            break;
        case FORMAT_JSON:
            return jsonRender($astDiff);
            break;
        case FORMAT_DEFAULT:
            return defaultRender($astDiff);
            break;
        default:
            throw new \Exception("Format  '{$format}' isn't correct");
    }
}
