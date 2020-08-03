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
        return collect($data)
            ->map(function ($item) use ($data, $complexKey, $generateLines) {
                $key = $complexKey ? $complexKey . '.' . $item['key'] : $item['key'];

                switch ($item['type']) {
                    case NODE_TYPE_CHILDREN:
                        return $generateLines($item['children'], $key);
                        break;
                    case NODE_TYPE_CHANGED:
                        $valOld = prepareVal($item['value'][NODE_TYPE_REMOVED]);
                        $valNew = prepareVal($item['value'][NODE_TYPE_ADDED]);
                        return "Property '{$key}' was changed. From '{$valOld}' to '{$valNew}'";
                    case NODE_TYPE_ADDED:
                        $val = prepareVal($item['value']);
                        return "Property '{$key}' was added with value: '{$val}'";
                        break;
                    case NODE_TYPE_REMOVED:
                        return "Property '{$key}' was removed";
                        break;
                    case NODE_TYPE_EQUAL:
                        return null;
                    default:
                        throw new \Exception("Item's type not correct: '{$item['type']}'");
                }
            })
            ->flatten()
            ->reject(function ($value) {
                return $value === null;
            })
            ->all();
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

function render(array $astDiff): string
{
    $lines = getDiffLines($astDiff);

    return implode(PHP_EOL, $lines) . PHP_EOL;
}
