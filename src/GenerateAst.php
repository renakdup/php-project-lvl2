<?php

declare(strict_types=1);

namespace CalcDiff\GenerateAst;

function generateAstDiff(object $dataBefore, object $dataAfter): array
{
    $keysBefore = array_keys(get_object_vars($dataBefore));
    $keysAfter = array_keys(get_object_vars($dataAfter));

    $commonKeys = array_unique(array_merge($keysBefore, $keysAfter));

    return collect($commonKeys)
        ->map(function ($key) use ($dataBefore, $dataAfter) {

            if (! isset($dataAfter->$key)) {
                return getNode($key, $dataBefore->$key, 'remove');
            } elseif (! isset($dataBefore->$key)) {
                return getNode($key, $dataAfter->$key, 'add');
            } elseif (is_object($dataBefore->$key) || is_object($dataAfter->$key)) {
                return getNode($key, null, 'equal', generateAstDiff($dataBefore->$key, $dataAfter->$key));
            } elseif ($dataAfter->$key === $dataBefore->$key) {
                return getNode($key, $dataBefore->$key, 'equal');
            } elseif ($dataAfter->$key !== $dataBefore->$key) {
                return [
                    'diff' => [
                        getNode($key, $dataAfter->$key, 'add'),
                        getNode($key, $dataBefore->$key, 'remove'),
                    ]
                ];
            }

            throw new \Exception("Changes' type not defined");
        })
        ->all();
}

function getNode(string $key, $value, string $type, ?array $children = null): array
{
    $node = [
        'key' => $key,
        'type' => $type,
    ];

    if ($value !== null) {
        $node['value'] = $value;
    } elseif ($children !== null) {
        $node['children'] = $children;
    }

    return $node;
}
