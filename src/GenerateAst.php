<?php

declare(strict_types=1);

namespace CalcDiff\GenerateAst;

const TYPE__OBJECT = 'object';
const TYPE__ARRAY = 'array';
const TYPE__SIMPLE = 'simple';

function generateAstDiff(object $dataBefore, object $dataAfter): array
{
    $keysBefore = array_keys(get_object_vars($dataBefore));
    $keysAfter = array_keys(get_object_vars($dataAfter));

    $commonKeys = array_unique(array_merge($keysBefore, $keysAfter));

    return collect($commonKeys)
        ->reduce(function ($acc, $key) use ($dataBefore, $dataAfter) {

            if (! isset($dataAfter->$key)) {
                $acc[$key] = getNode($dataBefore->$key, 'remove');
            } elseif (! isset($dataBefore->$key)) {
                $acc[$key] = getNode($dataAfter->$key, 'add');
            } elseif (is_object($dataBefore->$key) || is_object($dataAfter->$key)) {
                $acc[$key] = [
                    'action'   => 'equal',
                    'children' => generateAstDiff($dataBefore->$key, $dataAfter->$key),
                ];
            } elseif ($dataAfter->$key === $dataBefore->$key) {
                $acc[$key] = getNode($dataBefore->$key, 'equal');
            } elseif ($dataAfter->$key !== $dataBefore->$key) {
                $acc[$key]['diff'] = [
                    getNode($dataAfter->$key, 'add'),
                    getNode($dataBefore->$key, 'remove'),
                ];
            }

            throw new \Exception("Changes' type not defined");
        });
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
