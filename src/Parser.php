<?php

declare(strict_types=1);

namespace CalcDiff\Parser;

use Symfony\Component\Yaml\Yaml;

function parseContent(string $content, string $extension): object
{
    if ($extension === 'json') {
        return json_decode($content);
    } elseif ($extension === 'yaml') {
        return Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
    } else {
        throw new \Exception("File's type '{$extension}' doesn't support");
    }
}
