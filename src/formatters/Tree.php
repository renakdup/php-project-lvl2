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
                            $item['valueNew'],
                            '+',
                            $depth + 1
                        );
                        $oldLine = renderLine(
                            $item['key'],
                            $item['valueOld'],
                            '-',
                            $depth + 1
                        );
                        return array_merge($acc, [$newLine, $oldLine]);
                        break;
                    case NODE_TYPE_CHILDREN:
                        $sign = ' ';
                        $children = $generateLines($item['children'], $depth + 2);
                        $acc[] = renderChildrenLines($key, $children, $sign, $depth);
                        return $acc;
                    case NODE_TYPE_ADDED:
                        $sign = '+';
                        $acc[] = is_object($item['value'])
                            ? renderObjectValue($key, (array)$item['value'], $sign, $depth)
                            : renderKeyValueLines($key, $item['value'], $sign, $depth);
                        return $acc;
                        break;
                    case NODE_TYPE_REMOVED:
                        $sign = '-';
                        $acc[] = is_object($item['value'])
                            ? renderObjectValue($key, (array)$item['value'], $sign, $depth)
                            : renderKeyValueLines($key, $item['value'], $sign, $depth);
                        return $acc;
                    case NODE_TYPE_EQUAL:
                        $sign = ' ';
                        $acc[] = is_object($item['value'])
                            ? renderObjectValue($key, (array)$item['value'], $sign, $depth)
                            : renderKeyValueLines($key, $item['value'], $sign, $depth);
                        return $acc;
                    default:
                        throw new \Exception("Item's type not correct: '{$type}'");
                }
            }, []);
    };

    return $generateLines($data);
}

function renderLine(string $key, $val, string $sign, int $depth): string
{
    $offset = str_repeat('  ', $depth);

    if (is_bool($val)) {
        $val = $val ? 'true' : 'false';
    } elseif (is_array($val)) {
        $val = json_encode($val);
    }

    return "{$offset}{$sign} {$key}: {$val}";
}

function renderChildrenLines(string $key, array $children, string $sign, int $depth)
{
    $offset = str_repeat('  ', $depth + 1);
    $line = implode("\n", $children);

    return "{$offset}{$sign} {$key}: {\n{$line}\n  {$offset}}";
}

function renderObjectValue(string $key, array $obj, string $sign, int $depth): string
{
    $offset = str_repeat('  ', $depth + 1);
    $collect = collect($obj)
        ->map(function ($item, $k) use ($depth) {
            return renderLine($k, $item, ' ', $depth + 3);
        })->toArray();
    $line = implode("\n", $collect);

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

    return "{" . "\n" . implode("\n", $lines) . "\n" . "}" . "\n";
}
