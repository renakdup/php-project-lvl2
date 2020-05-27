<?php

namespace CalcDiff\Gendiff;

use function CalcDiff\formatters\DefaultFormat\render as defaultRender;
use function CalcDiff\formatters\Json\render as jsonRender;
use function CalcDiff\formatters\Plain\render as plainRender;
use function CalcDiff\GenerateAst\generateAstDiff;
use function CalcDiff\Parser\parseFile;
use function CalcDiff\Parser\parseContent;
use function CalcDiff\Parser\getFileType;

function gendiff(string $pathFileBefore, string $pathFileAfter, string $format): string
{
    $contentRawBefore = parseFile($pathFileBefore);
    $contentBefore = parseContent($contentRawBefore, getFileType($pathFileBefore));

    $contentRawAfter = parseFile($pathFileAfter);
    $contentAfter = parseContent($contentRawAfter, getFileType($pathFileAfter));

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
