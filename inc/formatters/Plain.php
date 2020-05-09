<?php

declare(strict_types=1);

namespace Renakdup\formatters\Plain;

use const Renakdup\GenerateAst\TYPE__OBJECT;

function getDiffLines(array $data, ?string $complexKey = null): array
{
    $keys = array_keys($data);

    return collect($keys)
        ->reduce(function ($acc, $key) use ($data, $complexKey) {
            $item = $data[$key];

            $fullKey = $complexKey ? $complexKey . '.' . $key : $key;

            if (isset($item['children'])) {
                return array_merge($acc, getDiffLines($item['children'], $fullKey));
            } elseif (isset($item['diff'])) {
                $acc[] = renderLine($fullKey, 'add', $item['diff'][0], $item['diff'][1]);
                return $acc;
            } elseif (isset($item['value']) && isset($item['type']) && $item['type'] === TYPE__OBJECT) {
                $acc[] = renderLine($fullKey, $item['action'], $item);
                return $acc;
            } elseif (isset($item['value']) && $item['action'] !== 'equal') {
                $acc[] = renderLine($fullKey, $item['action'], $item);
                return $acc;
            }

            return $acc;
        }, []);
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

function renderLine(string $key, string $action, array $itemNew, ?array $itemOld = null): string
{
    $valNew = prepareVal($itemNew['value'], $itemNew['type']);

    if ($action === 'add' && $itemOld) {
        $valOld = prepareVal($itemOld['value'], $itemOld['type']);
        $result = "Property '{$key}' was changed. From '{$valOld}' to '{$valNew}'";
    } elseif ($action === 'add') {
        $result = "Property '{$key}' was added with value: '{$valNew}'";
    } elseif ($action === 'remove') {
        $result = "Property '{$key}' was removed";
    } elseif ($action === 'equal') {
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
