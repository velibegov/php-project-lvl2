<?php

namespace Differ\Differ;

function genDiff(string $pathToFirstFile, string $pathToSecondFile, string $formatName = 'stylish'): string
{
    $firstContent = fileParse($pathToFirstFile);
    $secondContent = fileParse($pathToSecondFile);
    $differenceTree = makeDifferenceTree($firstContent, $secondContent);
    return getFormatter($formatName, $differenceTree);
}

function makeDifferenceTree(object $firstContent, object $secondContent): array
{
    $merged = array_merge((array)$firstContent, (array)$secondContent);
    $keys = array_keys($merged);
    $differenceTree = array_map(function ($key) use ($firstContent, $secondContent) {
        if (
            property_exists($firstContent, $key) && property_exists($secondContent, $key) &&
            is_object($firstContent->{$key}) && is_object($secondContent->{$key})
        ) {
            return [
                'key' => $key,
                'type' => 'parent',
                'children' => makeDifferenceTree($firstContent->{$key}, $secondContent->{$key})
            ];
        }
        if (!array_key_exists($key, (array)$secondContent)) {
            return [
                'key' => $key,
                'value' => $firstContent->{$key},
                'type' => 'removed'
            ];
        }
        if (!array_key_exists($key, (array)$firstContent)) {
            return [
                'key' => $key,
                'value' => $secondContent->{$key},
                'type' => 'added'
            ];
        }
        if ($firstContent->{$key} === $secondContent->{$key}) {
            return [
                'key' => $key,
                'value' => $firstContent->{$key},
                'type' => 'unmodified'
            ];
        } else {
            return [
                'key' => $key,
                'oldValue' => $firstContent->{$key},
                'newValue' => $secondContent->{$key},
                'type' => 'modified'
            ];
        }
    }, $keys);
    //usort($differenceTree, fn($a, $b) => $a['key'] <=> $b['key']);
    return $differenceTree;
}
