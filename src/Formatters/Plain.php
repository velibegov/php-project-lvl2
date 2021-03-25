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
        $result = 'null';
    }
    return $result;
}

function plainDiffFormat(array $differenceTree, string $fullPropertyName = ''): string
{
    $mapped = array_map(function ($value) use ($fullPropertyName): string {
        empty($fullPropertyName) ? $fullPropertyName .= "{$value['key']}" : $fullPropertyName .= ".{$value['key']}";
        $propertyNameParts[] = $fullPropertyName;
        $propertyName = implode('.', $propertyNameParts);
        switch ($value['type']) {
            case 'parent':
                return plainDiffFormat($value['children'], $propertyName);
            case 'modified':
                $oldValue = toString($value['oldValue']);
                $newValue = toString($value['newValue']);
                $result = "Property '{$propertyName}' was updated. From {$oldValue} to {$newValue}";
                break;
            case 'removed':
                $result = "Property '{$propertyName}' was removed";
                break;
            case 'added':
                $value = toString($value['value']);
                $result = "Property '{$propertyName}' was added with value: {$value}";
                break;
            default:
                $result = "";
                break;
        }
        return $result;
    }, $differenceTree);
    $filtered = array_filter($mapped, fn($item) => !empty($item));
    return implode("\n", $filtered);
}
