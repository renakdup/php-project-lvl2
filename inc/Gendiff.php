<?php

declare(strict_types=1);

namespace Renakdup\Gendiff;

use function Renakdup\formatters\DefaultFormat\render as defaultRender;
use function Renakdup\formatters\Plain\render as plainRender;
use function Renakdup\formatters\Json\render as jsonRender;
use function Renakdup\GenerateAst\generateAstDiff;

use const Renakdup\inc\CommandLine\FORMAT_DEFAULT;
use const Renakdup\inc\CommandLine\FORMAT_PLAIN;
use const Renakdup\inc\CommandLine\FORMAT_JSON;

function genDiff(object $contentBefore, object $contentAfter, string $format): string
{
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
