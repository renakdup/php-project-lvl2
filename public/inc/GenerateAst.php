<?php

declare(strict_types=1);

namespace Renakdup\GenerateAst;

function generateAstDiff(array $dataBefore, array $dataAfter): array
{
    $result = [];

    foreach ($dataBefore as $key => $val) {
        if (isset($dataAfter[$key]) && $dataAfter[$key] === $val) {
            $result[$key] = [
                'operator' => '=',
                'value' => $val
            ];
            unset($dataAfter[$key]);
        } elseif (isset($dataAfter[$key]) && $dataAfter[$key] !== $val) {
            $result[$key] = [
                [
                    'operator' => '+',
                    'value' => $dataAfter[$key],
                ],
                [
                    'operator' => '-',
                    'value' => $val,
                ],
            ];
            unset($dataAfter[$key]);
        } elseif (! isset($dataAfter[$key])) {
            $result[$key] = [
                'operator' => '-',
                'value' => $val
            ];
        }
    }

    $lostLines = collect($dataAfter)->map(function ($val, $key) {
        return [
            'operator' => '+',
            'value' => $val
        ];
    })->all();

    $result = array_merge($result, $lostLines);

    return $result;
}
