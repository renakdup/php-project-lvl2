<?php

declare(strict_types=1);

namespace CalcDiff\formatters\Tree;

function getDiffLines(array $data): array
{
    $generateLines = function ($data, int $depth = 0) use (&$generateLines): array {
        $keys = array_keys($data);

        return collect($keys)
            ->reduce(function ($acc, $index) use ($data, $depth, $generateLines) {
                $item = $data[$index];

                if (isset($item['diff'])) {
                    $diff = collect($item['diff'])
                        ->map(function ($item) use ($depth) {
                            return renderLine($item['key'], $item['value'], $item['compare_result'], $depth + 1);
                        })
                        ->all();

                    return array_merge($acc, $diff);
                }

                $key = $data[$index]['key'];

                if (isset($item['children'])) {
                    $children = $generateLines($item['children'], $depth + 2);
                    $acc[] = renderChildrenLines($key, $children, $item['compare_result'], $depth);
                    return $acc;
                } elseif (isset($item['value']) && is_object($item['value'])) {
                    $acc[] = renderObjectLines($key, (array)$item['value'], $item['compare_result'], $depth);
                    return $acc;
                } else {
                    $acc[] = renderKeyValueLines($key, $item['value'], $item['compare_result'], $depth);
                    return $acc;
                }
            }, []);
    };

    return $generateLines($data);
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
    $line = implode(PHP_EOL, $children);

    return "{$offset}{$sign} {$key}: {\n{$line}\n  {$offset}}";
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
