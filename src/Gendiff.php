<?php

namespace CalcDiff\Gendiff;

use function CalcDiff\formatters\Tree\render as defaultRender;
use function CalcDiff\formatters\Json\render as jsonRender;
use function CalcDiff\formatters\Plain\render as plainRender;
use function CalcDiff\GenerateAst\generateAstDiff;
use function CalcDiff\Parser\parseContent;

const FORMAT_TREE = 'tree';
const FORMAT_PLAIN = 'plain';
const FORMAT_JSON = 'json';

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
        case FORMAT_TREE:
            return defaultRender($astDiff);
            break;
        default:
            throw new \Exception("Format  '{$format}' isn't correct");
    }
}

function parseFile(string $pathToFile): string
{
    if (! file_exists($pathToFile)) {
        throw new \Exception("File '{$pathToFile}' not found");
    }

    return file_get_contents($pathToFile);
}

function getFileType(string $pathToFile): string
{
    return pathinfo($pathToFile, PATHINFO_EXTENSION);
}
