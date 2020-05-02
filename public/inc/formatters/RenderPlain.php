<?php

declare(strict_types=1);

namespace Renakdup\formatters\RenderPlain;

use const Renakdup\GenerateAst\TYPE__OBJECT;

function getDiffLines(array $data, ?string $complexKey = null): array
{
    $lines = [];

    foreach ($data as $key => $item) {
        $fullKey = $complexKey ? $complexKey . '.' . $key : $key;

        if (isset($item['children'])) {
            $lines = array_merge($lines, getDiffLines($item['children'], $fullKey));
        } elseif (isset($item['diff'])) {
            $lines[] = renderLine($fullKey, '+', $item['diff'][0], $item['diff'][1]);
        } elseif (isset($item['value']) && isset($item['type']) && $item['type'] === TYPE__OBJECT) {
            $lines[] = renderLine($fullKey, $item['operator'], $item);
        } elseif (isset($item['value']) && $item['operator'] !== '=') {
            $lines[] = renderLine($fullKey, $item['operator'], $item);
        }
    }

    return $lines;
}

function prepareVal($val, ?string $type = null)
{
    if ($type === TYPE__OBJECT) {
        $val = 'complex value';
    } elseif (is_bool($val)) {
        $val = $val ? 'true' : 'false';
    } elseif (is_array($val)) {
        $val = json_encode($val);
    }

    return $val;
}

function renderLine(string $key, string $operator, array $itemNew, ?array $itemOld = null): string
{
    $valNew = prepareVal($itemNew['value'], $itemNew['type']);

    if ($operator === '+' && $itemOld) {
        $valOld = prepareVal($itemOld['value'], $itemOld['type']);
        $result = "Property '{$key}' was changed. From '{$valOld}' to '{$valNew}'";
    } elseif ($operator === '+') {
        $result = "Property '{$key}' was added with value: '{$valNew}'";
    } elseif ($operator === '-') {
        $result = "Property '{$key}' was removed";
    } elseif ($operator === '=') {
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
