<?php

declare(strict_types=1);

namespace Renakdup\Gendiff;

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    if (! file_exists($pathToFile1) || file_exists($pathToFile2)) {
        throw new \Exception('file1 or file2 not found');
    }

    $file1 = file_get_contents($pathToFile1);
    $file2 = file_get_contents($pathToFile2);

    $parsedFile1 = json_decode($file1, true);
    $parsedFile2 = json_decode($file2, true);

    $renderLine = function ($key, $val, string $sign = ' '): string {
        return "  {$sign} {$key}: {$val}";
    };

    $lines = [];

    foreach ($parsedFile1 as $key => $val) {
        if (isset($parsedFile2[$key]) && $parsedFile2[$key] === $val) {
            $lines[] = $renderLine($key, $val);
            unset($parsedFile2[$key]);
        } elseif (isset($parsedFile2[$key]) && $parsedFile2[$key] !== $val) {
            $lines[] = $renderLine($key, $parsedFile2[$key], '+');
            $lines[] = $renderLine($key, $val, '-');
            unset($parsedFile2[$key]);
        } elseif (! isset($parsedFile2[$key])) {
            $lines[] = $renderLine($key, $val, '-');
        }
    }

    $lostFile2Lines = collect($parsedFile2)->map(function ($val, $key) use ($renderLine) {
        return $renderLine($key, $val, '+');
    })->all();

    $lines = array_merge($lines, $lostFile2Lines);

    return "{" . PHP_EOL . implode(PHP_EOL, $lines) . PHP_EOL . "}";
}
