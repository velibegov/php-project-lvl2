<?php

namespace Differ\Differ\Plain;

use Exception;

/**
 * @param string|int|bool|null|object|array|float $value
 * @return string
 */
function toString($value): string
{
    $result = is_object($value) ? "[complex value]" : var_export($value, true);
    if ($value === null) {
        return 'null';
    }
    return $result;
}

/**
 * @param array $differenceTree
 * @param array $parts
 * @return string
 * @throws Exception
 */
function format(array $differenceTree, array $parts = []): string
{
    $mapped = array_map(function ($value) use ($parts): string {
        $propertyNameParts = collect($parts);
        $merged = $propertyNameParts->merge($value['key']);
        $propertyName = implode('.', $merged->all());
        switch ($value['type']) {
            case 'parent':
                return format($value['children'], $merged->all());
            case 'modified':
                $oldValue = toString($value['old']);
                $newValue = toString($value['new']);
                $result = "Property '{$propertyName}' was updated. From {$oldValue} to {$newValue}";
                break;
            case 'removed':
                $result = "Property '{$propertyName}' was removed";
                break;
            case 'added':
                $added = toString($value['value']);
                $result = "Property '{$propertyName}' was added with value: {$added}";
                break;
            case 'unmodified':
                $result = ''; //must return string NOT null
                break;
            default:
                throw new Exception('Unknown value type ' . $value['type']);
        }
        return $result;
    },
        $differenceTree);
    return implode("\n", array_values(array_filter($mapped)));
}
