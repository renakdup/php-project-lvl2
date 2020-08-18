<?php

declare(strict_types=1);

namespace CalcDiff\formatters\Plain;

use const CalcDiff\GenerateAst\NODE_TYPE_ADDED;
use const CalcDiff\GenerateAst\NODE_TYPE_REMOVED;
use const CalcDiff\GenerateAst\NODE_TYPE_EQUAL;
use const CalcDiff\GenerateAst\NODE_TYPE_CHANGED;
use const CalcDiff\GenerateAst\NODE_TYPE_CHILDREN;

function render(array $astDiff): string
{
    $lines = getDiffLines($astDiff);

    return implode("\n", $lines) . "\n";
}

function getDiffLines(array $data): array
{
    $generateLines = function ($data, ?string $complexKey = null) use (&$generateLines): array {
        return collect($data)
            ->map(function ($item) use ($data, $complexKey, $generateLines) {
                $key = $complexKey ? $complexKey . '.' . $item['key'] : $item['key'];

                switch ($item['type']) {
                    case NODE_TYPE_CHILDREN:
                        return $generateLines($item['children'], $key);
                    case NODE_TYPE_CHANGED:
                        $valOld = getVal($item['valueOld']);
                        $valNew = getVal($item['valueNew']);
                        return "Property '{$key}' was changed. From '{$valOld}' to '{$valNew}'";
                    case NODE_TYPE_ADDED:
                        $val = getVal($item['valueNew']);
                        return "Property '{$key}' was added with value: '{$val}'";
                    case NODE_TYPE_REMOVED:
                        return "Property '{$key}' was removed";
                    case NODE_TYPE_EQUAL:
                        return null;
                    default:
                        throw new \Exception("Item's type not correct: '{$item['type']}'");
                }
            })
            ->all();
    };

    return collect($generateLines($data))
        ->flatten()
        ->reject(function ($value) {
            return $value === null;
        })
        ->all();
}

function getVal($val)
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
