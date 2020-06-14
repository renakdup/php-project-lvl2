<?php

declare(strict_types=1);

namespace CalcDiff\Converter;

use Symfony\Component\Yaml\Yaml;

function convert(string $content, string $dataType): object
{
    if ($dataType === 'json') {
        return json_decode($content);
    } elseif ($dataType === 'yaml') {
        return Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
    } else {
        throw new \Exception("Data's type '{$dataType}' doesn't support");
    }
}
