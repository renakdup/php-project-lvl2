<?php

declare(strict_types=1);

namespace Renakdup\Parse;

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
