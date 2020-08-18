<?php

declare(strict_types=1);

namespace CalcDiff\formatters\Tree;

use const CalcDiff\GenerateAst\NODE_TYPE_ADDED;
use const CalcDiff\GenerateAst\NODE_TYPE_REMOVED;
use const CalcDiff\GenerateAst\NODE_TYPE_EQUAL;
use const CalcDiff\GenerateAst\NODE_TYPE_CHANGED;
use const CalcDiff\GenerateAst\NODE_TYPE_CHILDREN;

function render(array $astDiff): string
{
    $lines = getDiffLines($astDiff);

    return "{" . "\n" . implode("\n", $lines) . "\n" . "}" . "\n";
}

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
                        $newLine = getLine(
                            $item['key'],
                            $item['valueNew'],
                            '+',
                            $depth + 1
                        );
                        $oldLine = getLine(
                            $item['key'],
                            $item['valueOld'],
                            '-',
                            $depth + 1
                        );
                        return array_merge($acc, [$newLine, $oldLine]);
                    case NODE_TYPE_CHILDREN:
                        $sign = ' ';
                        $children = $generateLines($item['children'], $depth + 2);
                        $acc[] = getChildrenLines($key, $children, $sign, $depth);
                        return $acc;
                    case NODE_TYPE_ADDED:
                        $acc[] = getValue($key, $item['valueNew'], '+', $depth);
                        return $acc;
                    case NODE_TYPE_REMOVED:
                        $acc[] = getValue($key, $item['valueOld'], '-', $depth);
                        return $acc;
                    case NODE_TYPE_EQUAL:
                        $acc[] = getValue($key, $item['valueNew'], ' ', $depth);
                        return $acc;
                    default:
                        throw new \Exception("Item's type not correct: '{$type}'");
                }
            }, []);
    };

    return $generateLines($data);
}

function getLine(string $key, $val, string $sign, int $depth): string
{
    $offset = str_repeat('  ', $depth);

    if (is_bool($val)) {
        $val = $val ? 'true' : 'false';
    } elseif (is_array($val)) {
        $val = json_encode($val);
    }

    return "{$offset}{$sign} {$key}: {$val}";
}

function getChildrenLines(string $key, array $children, string $sign, int $depth)
{
    $offset = str_repeat('  ', $depth + 1);
    $line = implode("\n", $children);

    return "{$offset}{$sign} {$key}: {\n{$line}\n  {$offset}}";
}

function getValue(string $key, $value, string $sign, int $depth)
{
    return is_object($value)
        ? getObjectValue($key, (array)$value, $sign, $depth)
        : getKeyValueLines($key, $value, $sign, $depth);
}

function getObjectValue(string $key, array $obj, string $sign, int $depth): string
{
    $offset = str_repeat('  ', $depth + 1);
    $collect = collect($obj)
        ->map(function ($item, $k) use ($depth) {
            return getLine($k, $item, ' ', $depth + 3);
        })->toArray();
    $line = implode("\n", $collect);

    return "{$offset}{$sign} {$key}: {\n{$line}\n  {$offset}}";
}

function getKeyValueLines(string $key, $value, string $action, int $depth): string
{
    $value = is_array($value) ? json_encode($value) : $value;

    return getLine($key, $value, $action, $depth + 1);
}
