<?php

declare(strict_types=1);

namespace Renakdup\formatters\RenderJson;

use const Renakdup\GenerateAst\TYPE__OBJECT;

function getDiffLines(array $data, int $depth = 0): array
{
    $lines = [];

    foreach ($data as $key => $item) {
        if (isset($item['children'])) {
            $lines[] = renderChildrenLines($key, $item['children'], $item['operator'], $depth);
        } elseif (isset($item['diff'])) {
            $diff = renderDiffLines($key, $item['diff'], $depth);
            $lines = array_merge($lines, $diff);
        } elseif (isset($item['value']) && isset($item['type']) && $item['type'] === TYPE__OBJECT) {
            $lines[] = renderObjectLines($key, json_decode($item['value'], true), $item['operator'], $depth);
        } elseif (isset($item['value'])) {
            $lines[] = renderKeyValueLines($key, $item['value'], $item['operator'], $depth);
        }
    }

    return $lines;
}

function getSign(string $operator): string
{
    return $operator === '=' ? ' ' : $operator;
}

function renderLine(string $key, $val, string $operator, int $depth): string
{
    $sign = getSign($operator);
    $offset = str_repeat('  ', $depth);

    if (is_bool($val)) {
        $val = $val ? 'true' : 'false';
    } elseif (is_array($val)) {
        $val = json_encode($val);
    }

    return "  {$offset}{$sign} {$key}: {$val}";
}

function renderChildrenLines(string $key, array $children, string $operator, int $depth)
{
    $sign = getSign($operator);
    $offset = str_repeat('  ', $depth + 1);
    $items = getDiffLines($children, $depth + 2);
    $line = implode(PHP_EOL, $items);

    return "{$offset}{$sign} {$key}: {\n{$line}\n  {$offset}}";
}

function renderDiffLines(string $key, array $diff, int $depth): array
{
    return collect($diff)
        ->map(function ($item) use ($key, $depth) {
            return renderLine($key, $item['value'], $item['operator'], $depth + 1);
        })->toArray();
}

function renderObjectLines(string $key, array $obj, string $operator, int $depth): string
{
    $sign = getSign($operator);
    $offset = str_repeat('  ', $depth + 2);
    $collect = collect($obj)
        ->map(function ($item, $k) use ($depth) {
            return renderLine($k, $item, '=', $depth + 2);
        })->toArray();
    $line = implode(PHP_EOL, $collect);

    return "{$offset}{$sign} {$key}: {\n{$line}\n  {$offset}}";
}

function renderKeyValueLines(string $key, string $value, string $operator, int $depth): string
{
    return renderLine($key, $value, $operator, $depth + 1);
}

function render(array $astDiff): string
{
    $lines = getDiffLines($astDiff);

    return "{" . PHP_EOL . implode(PHP_EOL, $lines) . PHP_EOL . "}" . PHP_EOL;
}
