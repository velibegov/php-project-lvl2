<?php

namespace Differ\Differ;

function genDiff(string $pathToFirstFile, string $pathToSecondFile, string $formatName = 'stylish'): string
{
    $firstContent = fileParse($pathToFirstFile);
    $secondContent = fileParse($pathToSecondFile);
    $differenceTree = makeDifferenceTree($firstContent, $secondContent);
    return formatDifference($formatName, $differenceTree);
}

function makeDifferenceTree(object $firstContent, object $secondContent): array
{
    $merged = array_merge((array)$firstContent, (array)$secondContent);
    $keys = array_keys($merged);
    $differenceTree = array_map(function ($key) use ($firstContent, $secondContent) {

        if (!property_exists($secondContent, $key)) {
            return [
                'key' => $key,
                'value' => $firstContent->{$key},
                'type' => 'removed'
            ];
        }
        if (!property_exists($firstContent, $key)) {
            return [
                'key' => $key,
                'value' => $secondContent->{$key},
                'type' => 'added'
            ];
        }
        if (is_object($firstContent->{$key}) && is_object($secondContent->{$key})) {
            return [
                'key' => $key,
                'type' => 'parent',
                'children' => makeDifferenceTree($firstContent->{$key}, $secondContent->{$key})
            ];
        }
        if ($firstContent->{$key} === $secondContent->{$key}) {
            return [
                'key' => $key,
                'value' => $firstContent->{$key},
                'type' => 'unmodified'
            ];
        }
        if ($firstContent->{$key} !== $secondContent->{$key}) {
            return [
                'key' => $key,
                'oldValue' => $firstContent->{$key},
                'newValue' => $secondContent->{$key},
                'type' => 'modified'
            ];
        }
    }, $keys);
    return arr_usort($differenceTree, fn($value1, $value2) => $value1['key'] <=> $value2['key']);
}
