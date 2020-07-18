<?php

declare(strict_types=1);

namespace CalcDiff\formatters\Plain;

function getDiffLines(array $data): array
{
    $generateLines = function ($data, ?string $complexKey = null) use (&$generateLines): array {
        $keys = array_keys($data);

        return collect($keys)
            ->reduce(function ($acc, $key) use ($data, $complexKey, $generateLines) {
                $item = $data[$key];

                $key = isset($item['diff']) ? $item['diff'][0]['key'] : $item['key'];
                $fullKey = $complexKey ? $complexKey . '.' . $key : $key;

                if (isset($item['children'])) {
                    $mr = $generateLines($item['children'], $fullKey);
                    return array_merge($acc, $mr);
                } elseif (isset($item['diff'])) {
                    $acc[] = renderLine($fullKey, 'add', $item['diff'][0]['value'], $item['diff'][1]['value']);
                    return $acc;
                } elseif (isset($item['value']) && is_object($item['value'])) {
                    $acc[] = renderLine($fullKey, $item['type'], $item['value']);
                    return $acc;
                } elseif (isset($item['value']) && $item['type'] !== 'equal') {
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

    if ($type === 'add' && $valBefore !== null) {
        $valBefore = prepareVal($valBefore);
        $result = "Property '{$key}' was changed. From '{$valBefore}' to '{$valAfter}'";
    } elseif ($type === 'add') {
        $result = "Property '{$key}' was added with value: '{$valAfter}'";
    } elseif ($type === 'remove') {
        $result = "Property '{$key}' was removed";
    } elseif ($type === 'equal') {
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
