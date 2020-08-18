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
                return getNode($key, null, $dataBefore->$key, NODE_TYPE_REMOVED);
            } elseif (! isset($dataBefore->$key)) {
                return getNode($key, $dataAfter->$key, null, NODE_TYPE_ADDED);
            } elseif (is_object($dataBefore->$key) || is_object($dataAfter->$key)) {
                return getNode(
                    $key,
                    null,
                    null,
                    NODE_TYPE_CHILDREN,
                    generateAstDiff($dataBefore->$key, $dataAfter->$key)
                );
            } elseif ($dataAfter->$key === $dataBefore->$key) {
                return getNode($key, $dataAfter->$key, $dataBefore->$key, NODE_TYPE_EQUAL);
            } elseif ($dataAfter->$key !== $dataBefore->$key) {
                return getNode($key, $dataAfter->$key, $dataBefore->$key, NODE_TYPE_CHANGED);
            }

            throw new \Exception("Changes' type not defined for key: {$key} and dataBefore: "
                . var_export($dataBefore) . "and dataAfter: " . var_dump($dataAfter));
        })
        ->all();
}

function getNode(string $key, $valueNew, $valueOld, string $type, ?array $children = null): array
{
    return [
        'key' => $key,
        'type' => $type,
        'children' => $children,
        'valueNew' => $valueNew,
        'valueOld' => $valueOld,
    ];
}
