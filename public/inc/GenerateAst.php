<?php

declare(strict_types=1);

namespace Renakdup\GenerateAst;

const TYPE__OBJECT = 'object';
const TYPE__ARRAY = 'array';
const TYPE__SIMPLE = 'simple';

function generateAstDiff($dataBefore, $dataAfter): array
{
    $result = [];
    $added = [];

    foreach ($dataBefore as $key => $val) {
        if (is_object($val) && ! isset($dataAfter->$key)) {
            $result[$key] = getNode($dataBefore->$key, '-');
        } elseif (is_object($val) && isset($dataAfter->$key)) {
            $result[$key] = [
                'operator' => '=',
                'children' => generateAstDiff($dataBefore->$key, $dataAfter->$key)
            ];
            $added[] = $key;
        } elseif (isset($dataAfter->$key) && $dataAfter->$key === $val) {
            $result[$key] = getNode($val, '=');
            $added[] = $key;
        } elseif (isset($dataAfter->$key) && $dataAfter->$key !== $val) {
            $result[$key]['diff'] = [
                getNode($dataAfter->$key, '+'),
                getNode($val, '-'),
            ];
            $added[] = $key;
        } elseif (! isset($dataAfter->$key)) {
            $result[$key] = getNode($val, '-');
            $added[] = $key;
        }
    }

    $collect = collect($dataAfter)
        ->reject(function ($val, $key) use ($added) {
            return in_array($key, $added);
        })
        ->map(function ($val, $key) {
            return getNode($val, '+');
        })
        ->toArray();

    $result = array_merge($result, $collect);

    return $result;
}

function getNode($value, string $operator): array
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
        'operator' => $operator,
        'type' => $type,
        'value' => $value
    ];
}
