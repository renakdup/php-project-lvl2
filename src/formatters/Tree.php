<?php

declare(strict_types=1);

namespace CalcDiff\formatters\Tree;

use const CalcDiff\GenerateAst\NODE_TYPE_ADDED;
use const CalcDiff\GenerateAst\NODE_TYPE_REMOVED;
use const CalcDiff\GenerateAst\NODE_TYPE_EQUAL;
use const CalcDiff\GenerateAst\NODE_TYPE_CHANGED;
use const CalcDiff\GenerateAst\NODE_TYPE_CHILDREN;

function getDiffLines(array $data): array
{
    $generateLines = function ($data, int $depth = 0) use (&$generateLines): array {
        $keys = array_keys($data);

        return collect($keys)
            ->reduce(function ($acc, $index) use ($data, $depth, $generateLines) {
                $item = $data[$index];
                $key = $item['key'];
                $type = $item['type'];

                switch ($type) {
                    case NODE_TYPE_CHANGED:
                        $newLine = renderLine(
                            $item['key'],
                            $item['value'][NODE_TYPE_ADDED],
                            NODE_TYPE_ADDED,
                            $depth + 1
                        );
                        $oldLine = renderLine(
                            $item['key'],
                            $item['value'][NODE_TYPE_REMOVED],
                            NODE_TYPE_REMOVED,
                            $depth + 1
                        );
                        return array_merge($acc, [$newLine, $oldLine]);
                        break;
                    case NODE_TYPE_CHILDREN:
                        $children = $generateLines($item['children'], $depth + 2);
                        $acc[] = renderChildrenLines($key, $children, $item['type'], $depth);
                        return $acc;
                    case NODE_TYPE_ADDED:
                    case NODE_TYPE_REMOVED:
                    case NODE_TYPE_EQUAL:
                        $acc[] = is_object($item['value'])
                            ? renderObjectLines($key, (array)$item['value'], $item['type'], $depth)
                            : renderKeyValueLines($key, $item['value'], $item['type'], $depth);
                        return $acc;
                    default:
                        throw new \Exception("Item's type not correct: '{$type}'");
                }
            }, []);
    };

    return $generateLines($data);
}

function getSign(string $action): string
{
    switch ($action) {
        case NODE_TYPE_ADDED:
            return '+';
            break;
        case NODE_TYPE_REMOVED:
            return '-';
            break;
        case NODE_TYPE_EQUAL:
        case NODE_TYPE_CHILDREN:
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
    $line = implode(PHP_EOL, $children);

    return "{$offset}{$sign} {$key}: {\n{$line}\n  {$offset}}";
}

function renderObjectLines(string $key, array $obj, string $action, int $depth): string
{
    $sign = getSign($action);
    $offset = str_repeat('  ', $depth + 1);
    $collect = collect($obj)
        ->map(function ($item, $k) use ($depth) {
            return renderLine($k, $item, NODE_TYPE_EQUAL, $depth + 3);
        })->toArray();
    $line = implode(PHP_EOL, $collect);

    return "{$offset}{$sign} {$key}: {\n{$line}\n  {$offset}}";
}

function renderKeyValueLines(string $key, $value, string $action, int $depth): string
{
    $value = is_array($value) ? json_encode($value) : $value;

    return renderLine($key, $value, $action, $depth + 1);
}

function render(array $astDiff): string
{
    $lines = getDiffLines($astDiff);

    return "{" . PHP_EOL . implode(PHP_EOL, $lines) . PHP_EOL . "}" . PHP_EOL;
}
