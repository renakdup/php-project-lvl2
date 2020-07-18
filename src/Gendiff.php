<?php

namespace CalcDiff\Gendiff;

use function CalcDiff\formatters\Tree\render as renderTree;
use function CalcDiff\formatters\Json\render as renderJson;
use function CalcDiff\formatters\Plain\render as renderPlain;
use function CalcDiff\GenerateAst\generateAstDiff;
use function CalcDiff\Converter\parser;

const FORMAT_TREE = 'tree';
const FORMAT_PLAIN = 'plain';
const FORMAT_JSON = 'json';

function gendiff(string $pathFileBefore, string $pathFileAfter, string $format): string
{
    $contentRawBefore = readFile($pathFileBefore);
    $contentBefore = parser($contentRawBefore, getFileType($pathFileBefore));

    $contentRawAfter = readFile($pathFileAfter);
    $contentAfter = parser($contentRawAfter, getFileType($pathFileAfter));

    $astDiff = generateAstDiff($contentBefore, $contentAfter);

    switch ($format) {
        case FORMAT_PLAIN:
            return renderPlain($astDiff);
            break;
        case FORMAT_JSON:
            return renderJson($astDiff);
            break;
        case FORMAT_TREE:
            return renderTree($astDiff);
            break;
        default:
            throw new \Exception("Format  '{$format}' isn't correct");
    }
}

function readFile(string $pathToFile): string
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
