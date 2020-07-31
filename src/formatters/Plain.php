<?php

declare(strict_types=1);

namespace CalcDiff\formatters\Plain;

use const CalcDiff\GenerateAst\NODE_TYPE_ADDED;
use const CalcDiff\GenerateAst\NODE_TYPE_REMOVED;
use const CalcDiff\GenerateAst\NODE_TYPE_EQUAL;
use const CalcDiff\GenerateAst\NODE_TYPE_CHANGED;
use const CalcDiff\GenerateAst\NODE_TYPE_CHILDREN;

function getDiffLines(array $data): array
{
    $generateLines = function ($data, ?string $complexKey = null) use (&$generateLines): array {
        $keys = array_keys($data);

        return collect($keys)
            ->reduce(function ($acc, $key) use ($data, $complexKey, $generateLines) {
                $item = $data[$key];
                $key = isset($item['diff']) ? $item['diff'][0]['key'] : $item['key'];
                $fullKey = $complexKey ? $complexKey . '.' . $key : $key;
                $type = $item['type'];

                if ($type === NODE_TYPE_CHILDREN) {
                    $mr = $generateLines($item['children'], $fullKey);
                    return array_merge($acc, $mr);
                } elseif ($type === NODE_TYPE_CHANGED) {
                    $acc[] = renderLine(
                        $fullKey,
                        NODE_TYPE_CHANGED,
                        $item['value'][NODE_TYPE_ADDED],
                        $item['value'][NODE_TYPE_REMOVED]
                    );
                    return $acc;
                } elseif (is_object($item['value'])) {
                    $acc[] = renderLine($fullKey, $item['type'], $item['value']);
                    return $acc;
                } elseif ($item['type'] !== NODE_TYPE_EQUAL) {
                    $acc[] = renderLine($fullKey, $item['type'], $item['value']);
                    return $acc;
                }

                return $acc;
            }, []);
    };

    return $generateLines($data);
}

function prepareVal($val)
{
    if (is_bool($val)) {
        $val = $val ? 'true' : 'false';
    } elseif (is_array($val)) {
        $val = json_encode($val);
    } elseif (is_object($val)) {
        $val = 'complex value';
    }

    return $val;
}

function renderLine(string $key, string $type, $valAfter, $valBefore = null): string
{
    $valAfter = prepareVal($valAfter);

    if ($type === NODE_TYPE_CHANGED) {
        $valBefore = prepareVal($valBefore);
        $result = "Property '{$key}' was changed. From '{$valBefore}' to '{$valAfter}'";
    } elseif ($type === NODE_TYPE_ADDED) {
        $result = "Property '{$key}' was added with value: '{$valAfter}'";
    } elseif ($type === NODE_TYPE_REMOVED) {
        $result = "Property '{$key}' was removed";
    } elseif ($type === NODE_TYPE_EQUAL) {
        $result = '';
    } else {
        throw new \Exception("Condition not defined");
    }

    return $result;
}

function render(array $astDiff): string
{
    $lines = getDiffLines($astDiff);

    return implode(PHP_EOL, $lines) . PHP_EOL;
}
