<?php

namespace Differ\Differ\Plain;

/**
 * @param string|int|bool|null|object|array|float $value
 * @return string
 */
function toString($value): string
{
    $result = is_object($value) ? "[complex value]" : $result = var_export($value, true);
    if ($value === null) {
        return 'null';
    }
    return $result;
}

/*function plainDiffFormat(array $differenceTree, array $propertyNameParts = []): string
{
    $mapped = array_map(function ($value) use ($propertyNameParts): string {
        $propertyNameParts[] = "{$value['key']}";
        $propertyName = implode('.', $propertyNameParts);
        switch ($value['type']) {
            case 'parent':
                return plainDiffFormat($value['children'], $propertyNameParts);
            case 'modified':
                $oldValue = toString($value['oldValue']);
                $newValue = toString($value['newValue']);
                $result = "Property '{$propertyName}' was updated. From {$oldValue} to {$newValue}";
                break;
            case 'removed':
                $result = "Property '{$propertyName}' was removed";
                break;
            case 'added':
                $added = toString($value['value']);
                $result = "Property '{$propertyName}' was added with value: {$added}";
                break;
            default:
                $result = "";
                break;
        }
        return $result;
    }, $differenceTree);
    $filtered = array_filter($mapped, fn($item) => $item !== '');
    return implode("\n", $filtered);
}*/

function plainDiffFormat(array $differenceTree, array $parts = []): string
{
    $mapped = array_map(function ($value) use ($parts): string {
        $propertyNameParts = arr_push($parts, "{$value['key']}");
        $propertyName = implode('.', $propertyNameParts);
        switch ($value['type']) {
            case 'parent':
                return plainDiffFormat($value['children'], $propertyNameParts);
            case 'modified':
                $oldValue = toString($value['oldValue']);
                $newValue = toString($value['newValue']);
                $result = "Property '{$propertyName}' was updated. From {$oldValue} to {$newValue}";
                break;
            case 'removed':
                $result = "Property '{$propertyName}' was removed";
                break;
            case 'added':
                $added = toString($value['value']);
                $result = "Property '{$propertyName}' was added with value: {$added}";
                break;
            default:
                $result = "";
                break;
        }
        return $result;
    }, $differenceTree);
    $filtered = array_filter($mapped, fn($item) => $item !== '');
    return implode("\n", $filtered);
}
