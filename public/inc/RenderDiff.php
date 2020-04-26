<?php

declare(strict_types=1);

namespace Renakdup\RenderDiff;

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
