<?php

namespace Php\Project\Lvl2\Plain;

function toString(mixed $value): string
{
    is_object($value) ? $result = "[complex value]" : $result = var_export($value, true);
    if ($value === null) {
        $result = 'null';
    }
    return $result;
}

function plainDiffFormat(array $differenceTree, string $fullPropertyName = ''): string
{
    $mapped = array_map(function ($value) use ($fullPropertyName) {
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
