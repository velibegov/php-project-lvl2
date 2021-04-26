<?php

namespace Differ\Differ;

use Exception;
use stdClass;

/**
 * @param string $pathToFirstFile
 * @param string $pathToSecondFile
 * @param string $formatName
 * @return string
 * @throws \Exception
 */
function genDiff(string $pathToFirstFile, string $pathToSecondFile, string $formatName = 'stylish'): string
{
    $firstContent = getContent($pathToFirstFile);
    $secondContent = getContent($pathToSecondFile);
    $differenceTree = makeDifferenceTree($firstContent, $secondContent);
    return formatDifference($formatName, $differenceTree);
}

/**
 * @param object $firstContent
 * @param object $secondContent
 * @return array
 */
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
        return [
            'key' => $key,
            'old' => $firstContent->{$key},
            'new' => $secondContent->{$key},
            'type' => 'modified'
        ];
    }, $keys);
    return collect($differenceTree)
        ->sortBy([fn($value1, $value2) => $value1['key'] <=> $value2['key']])
        ->values()
        ->all();
}

/**
 * @param string $filePath
 * @return string
 * @throws Exception
 */
function readFile(string $filePath): string
{
    if (file_exists($filePath)) {
        $data = file_get_contents($filePath);
    } else {
        throw new Exception('File not exists ' . $filePath);
    }
    if (is_string($data)) {
        return $data;
    } else {
        throw new Exception('Incorrect file content ' . $filePath);
    }
}

/**
 * @param string $filePath
 * @return stdClass
 * @throws Exception
 */
function getContent(string $filePath): stdClass
{
    $data = readFile($filePath);
    $dataType = pathinfo($filePath, PATHINFO_EXTENSION);
    return parseData($data, $dataType);
}
