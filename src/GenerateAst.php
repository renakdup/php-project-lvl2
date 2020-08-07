<?php

declare(strict_types=1);

namespace CalcDiff\GenerateAst;

const NODE_TYPE_ADDED = 'added';
const NODE_TYPE_REMOVED = 'removed';
const NODE_TYPE_EQUAL = 'equal';
const NODE_TYPE_CHANGED = 'changed';
const NODE_TYPE_CHILDREN = 'children';

function generateAstDiff(object $dataBefore, object $dataAfter): array
{
    $keysBefore = array_keys(get_object_vars($dataBefore));
    $keysAfter = array_keys(get_object_vars($dataAfter));

    $commonKeys = array_unique(array_merge($keysBefore, $keysAfter));

    return collect($commonKeys)
        ->map(function ($key) use ($dataBefore, $dataAfter) {

            if (! isset($dataAfter->$key)) {
                return getNode($key, $dataBefore->$key, NODE_TYPE_REMOVED);
            } elseif (! isset($dataBefore->$key)) {
                return getNode($key, $dataAfter->$key, NODE_TYPE_ADDED);
            } elseif (is_object($dataBefore->$key) || is_object($dataAfter->$key)) {
                return getNode(
                    $key,
                    null,
                    NODE_TYPE_CHILDREN,
                    null,
                    generateAstDiff($dataBefore->$key, $dataAfter->$key)
                );
            } elseif ($dataAfter->$key === $dataBefore->$key) {
                return getNode($key, $dataBefore->$key, NODE_TYPE_EQUAL);
            } elseif ($dataAfter->$key !== $dataBefore->$key) {
                return getNode($key, null, NODE_TYPE_CHANGED, [
                    'old' => $dataBefore->$key,
                    'new' => $dataAfter->$key,
                ]);
            }

            throw new \Exception("Changes' type not defined for key: {$key} and dataBefore: "
                . var_export($dataBefore) . "and dataAfter: " . var_dump($dataAfter));
        })
        ->all();
}

function getNode(string $key, $value, string $type, $diffValue = null, ?array $children = null): array
{
    $node = [
        'key' => $key,
        'type' => $type,
        'children' => $children,
    ];

    if ($diffValue === null) {
        $node['value'] = $value;
    } else {
        $node['valueOld'] = $diffValue['old'];
        $node['valueNew'] = $diffValue['new'];
    }

    return $node;
}
