<?php

declare(strict_types=1);

namespace CalcDiff\formatters\Tree;

use const CalcDiff\GenerateAst\TYPE__ARRAY;
use const CalcDiff\GenerateAst\TYPE__OBJECT;
use const CalcDiff\GenerateAst\TYPE__SIMPLE;

function getDiffLines(array $data, int $depth = 0): array
{
    $keys = array_keys($data);

    return collect($keys)
        ->reduce(function ($acc, $key) use ($data, $depth) {
            $item = $data[$key];

            if (isset($item['children'])) {
                $acc[] = renderChildrenLines($key, $item['children'], $item['action'], $depth);
                return $acc;
            } elseif (isset($item['diff'])) {
                $diff = renderDiffLines($key, $item['diff'], $depth);
                return array_merge($acc, $diff);
            } elseif (isset($item['value']) && $item['type'] === TYPE__OBJECT) {
                $acc[] = renderObjectLines($key, json_decode($item['value'], true), $item['action'], $depth);
                return $acc;
            } elseif (isset($item['type']) && ($item['type'] === TYPE__SIMPLE || $item['type'] === TYPE__ARRAY)) {
                $acc[] = renderKeyValueLines($key, $item['value'], $item['action'], $depth);
                return $acc;
            }

            return $acc;
        }, []);
}

function getSign(string $action): string
{
    switch ($action) {
        case 'add':
            return '+';
            break;
        case 'remove':
            return '-';
            break;
        case 'equal':
            return ' ';
            break;
        default:
            throw new \Exception("Action '{$action}' isn't correct");
    }
}

function renderLine(string $key, $val, string $action, int $depth): string
{
    $sign = getSign($action);
    $offset = str_repeat('  ', $depth);

    if (is_bool($val)) {
        $val = $val ? 'true' : 'false';
    } elseif (is_array($val)) {
        $val = json_encode($val);
    }

    return "{$offset}{$sign} {$key}: {$val}";
}

function renderChildrenLines(string $key, array $children, string $action, int $depth)
{
    $sign = getSign($action);
    $offset = str_repeat('  ', $depth + 1);
    $items = getDiffLines($children, $depth + 2);
    $line = implode(PHP_EOL, $items);

    return "{$offset}{$sign} {$key}: {\n{$line}\n  {$offset}}";
}

function renderDiffLines(string $key, array $diff, int $depth): array
{
    return collect($diff)
        ->map(function ($item) use ($key, $depth) {
            return renderLine($key, $item['value'], $item['action'], $depth + 1);
        })->toArray();
}

function renderObjectLines(string $key, array $obj, string $action, int $depth): string
{
    $sign = getSign($action);
    $offset = str_repeat('  ', $depth + 1);
    $collect = collect($obj)
        ->map(function ($item, $k) use ($depth) {
            return renderLine($k, $item, 'equal', $depth + 3);
        })->toArray();
    $line = implode(PHP_EOL, $collect);

    return "{$offset}{$sign} {$key}: {\n{$line}\n  {$offset}}";
}

function renderKeyValueLines(string $key, string $value, string $action, int $depth): string
{
    return renderLine($key, $value, $action, $depth + 1);
}

function render(array $astDiff): string
{
    $lines = getDiffLines($astDiff);

    return "{" . PHP_EOL . implode(PHP_EOL, $lines) . PHP_EOL . "}" . PHP_EOL;
}
