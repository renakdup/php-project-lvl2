<?php

declare(strict_types=1);

namespace CalcDiff\GenerateAst;

const TYPE__OBJECT = 'object';
const TYPE__ARRAY = 'array';
const TYPE__SIMPLE = 'simple';

function generateAstDiff(object $dataBefore, object $dataAfter): array
{
    $added = [];

    $keys = array_keys(get_object_vars($dataBefore));

    $result = collect($keys)
        ->reduce(function ($acc, $key) use ($dataBefore, $dataAfter, &$added) {
            $item = $dataBefore->$key;

            if (is_object($item) && ! isset($dataAfter->$key)) {
                $acc[$key] = getNode($dataBefore->$key, 'remove');
            } elseif (is_object($item) && isset($dataAfter->$key)) {
                $acc[$key] = [
                    'action' => 'equal',
                    'children' => generateAstDiff($dataBefore->$key, $dataAfter->$key)
                ];
                $added[] = $key;
            } elseif (isset($dataAfter->$key) && $dataAfter->$key === $item) {
                $acc[$key] = getNode($item, 'equal');
                $added[] = $key;
            } elseif (isset($dataAfter->$key) && $dataAfter->$key !== $item) {
                $acc[$key]['diff'] = [
                    getNode($dataAfter->$key, 'add'),
                    getNode($item, 'remove'),
                ];
                $added[] = $key;
            } elseif (! isset($dataAfter->$key)) {
                $acc[$key] = getNode($item, 'remove');
                $added[] = $key;
            }

            return $acc;
        });

    $collect = collect($dataAfter)
        ->reject(function ($val, $key) use ($added) {
            return in_array($key, $added);
        })
        ->map(function ($val, $key) {
            return getNode($val, 'add');
        })
        ->toArray();

    $result = array_merge($result, $collect);

    return $result;
}

function getNode($value, string $action): array
{
    if (is_object($value)) {
        $type = TYPE__OBJECT;
        $value = json_encode($value);
    } elseif (is_array($value)) {
        $type = TYPE__ARRAY;
        $value = json_encode($value);
    } else {
        $type = TYPE__SIMPLE;
    }

    return [
        'action' => $action,
        'type' => $type,
        'value' => $value
    ];
}
