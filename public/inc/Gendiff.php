<?php

declare(strict_types=1);

namespace Renakdup\Gendiff;

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    if (! file_exists($pathToFile1) || ! file_exists($pathToFile2)) {
        throw new \Exception("Files {$pathToFile1} or {$pathToFile2} not found");
    }

    $file1 = file_get_contents($pathToFile1);
    $file2 = file_get_contents($pathToFile2);

    $astDiff = generateAstDiff($file1, $file2);
    $result = outputDiff($astDiff);

    return $result;
}

function generateAstDiff(string $file1, string $file2): array
{
    $result = [];

    $parsedFile1 = json_decode($file1, true);
    $parsedFile2 = json_decode($file2, true);

    foreach ($parsedFile1 as $key => $val) {
        if (isset($parsedFile2[$key]) && $parsedFile2[$key] === $val) {
            $result[$key] = [
                'operator' => '=',
                'value' => $val
            ];
            unset($parsedFile2[$key]);
        } elseif (isset($parsedFile2[$key]) && $parsedFile2[$key] !== $val) {
            $result[$key] = [
                [
                    'operator' => '+',
                    'value' => $parsedFile2[$key],
                ],
                [
                    'operator' => '-',
                    'value' => $val,
                ],
            ];
            unset($parsedFile2[$key]);
        } elseif (! isset($parsedFile2[$key])) {
            $result[$key] = [
                'operator' => '-',
                'value' => $val
            ];
        }
    }

    $lostFile2Lines = collect($parsedFile2)->map(function ($val, $key) {
        return [
            'operator' => '+',
            'value' => $val
        ];
    })->all();

    $result = array_merge($result, $lostFile2Lines);

    return $result;
}

function getDiffLines(array $diff, ?string $parentKey = null): array
{
    $lines = [];

    foreach ($diff as $key => $item) {
        if (is_array($item) && ! isset($item['operator'])) {
            $lines = array_merge($lines, getDiffLines($item, $key));
            continue;
        }

        if (isset($parentKey)) {
            $lines[] = renderLine($parentKey, $item['value'], $item['operator']);
        } else {
            $lines[] = renderLine($key, $item['value'], $item['operator']);
        }
    }

    return $lines;
}

function renderLine($key, $val, string $sign): string
{
    $sign = $sign === '=' ? ' ' : $sign;

    $val = is_bool($val) ? var_export($val, true) : $val;
    return "  {$sign} {$key}: {$val}";
}

function outputDiff(array $astDiff): string
{
    $lines = getDiffLines($astDiff);

    return "{" . PHP_EOL . implode(PHP_EOL, $lines) . PHP_EOL . "}" . PHP_EOL;
}
