<?php

declare(strict_types=1);

namespace Renakdup\GenerateAst;

function generateAstDiff($dataBefore, $dataAfter): array
{
    $result = [];
    $added = [];

    foreach ($dataBefore as $key => $val) {
        if (is_object($val) && ! isset($dataAfter->$key)) {
            $result[$key] = [
                'operator' => '-',
                'value' => (array) $dataBefore->$key
            ];
        } elseif (is_object($val) && isset($dataAfter->$key)) {
            $result[$key] = [
                'operator' => '=',
                'children' => generateAstDiff($dataBefore->$key, $dataAfter->$key)
            ];
            $added[] = $key;
        } elseif (isset($dataAfter->$key) && $dataAfter->$key === $val) {
            $result[$key] = [
                'operator' => '=',
                'value' => $val
            ];
            $added[] = $key;
        } elseif (isset($dataAfter->$key) && $dataAfter->$key !== $val) {
            $result[$key] = [
                [
                    'operator' => '+',
                    'value' => $dataAfter->$key,
                ],
                [
                    'operator' => '-',
                    'value' => $val,
                ],
            ];
            $added[] = $key;
        } elseif (! isset($dataAfter->$key)) {
            $result[$key] = [
                'operator' => '-',
                'value' => $val
            ];
        }
    }

    $collect = collect($dataAfter)
        ->reject(function ($val, $key) use ($added) {
            return in_array($key, $added);
        })
        ->map(function ($val, $key) {
            return [
                'operator' => '+',
                'value'    => (array)$val,
            ];
        })
        ->toArray();

    $result = array_merge($result, $collect);

    return $result;
}
