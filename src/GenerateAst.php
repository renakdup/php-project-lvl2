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
                return getNode($key, null, NODE_TYPE_CHILDREN, generateAstDiff($dataBefore->$key, $dataAfter->$key));
            } elseif ($dataAfter->$key === $dataBefore->$key) {
                return getNode($key, $dataBefore->$key, NODE_TYPE_EQUAL);
            } elseif ($dataAfter->$key !== $dataBefore->$key) {
                return getNode($key, [
                    NODE_TYPE_REMOVED => $dataBefore->$key,
                    NODE_TYPE_ADDED => $dataAfter->$key,
                ], NODE_TYPE_CHANGED);
            }

            throw new \Exception("Changes' type not defined");
        })
        ->all();
}

function getNode(string $key, $value, string $type, ?array $children = null): array
{
    return [
        'key' => $key,
        'type' => $type,
        'value' => $value,
        'children' => $children,
    ];
}
