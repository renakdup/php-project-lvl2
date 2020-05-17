<?php

declare(strict_types=1);

namespace Renakdup\ParseFile;

use Symfony\Component\Yaml\Yaml;

function parseFile(string $pathToFile): object
{
    if (! file_exists($pathToFile)) {
        throw new \Exception("File '{$pathToFile}' not found");
    }

    $content = file_get_contents($pathToFile);
    $extension = getFileType($pathToFile);

    if ($extension === 'json') {
        return json_decode($content);
    } elseif ($extension === 'yaml') {
        return Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
    } else {
        throw new \Exception("File's type '{$extension}' doesn't support");
    }
}

function getFileType(string $pathToFile): string
{
    return pathinfo($pathToFile, PATHINFO_EXTENSION);
}
