<?php

namespace Differ\Differ;

/**
 * @param string|int|bool|null|object|array|float $value
 * @return string
 */
function toString($value): string
{
    if ($value === null) {
        return 'null';
    }
    return trim(var_export($value, true), "'");
}

function getTypeIndent(string $type): string
{
    switch ($type) {
        case 'newValue':
        case 'added':
            return '+ ';
        case 'oldValue':
        case 'removed':
            return '- ';
        default:
            return '  ';
    }
}

function getIndent(int $depth, string $position = 'front'): string
{
    $baseIndent = " ";
    switch ($position) {
        case 'front':
            return str_repeat($baseIndent, $depth * 4 - 2);
        case 'back':
            return str_repeat($baseIndent, $depth * 4 - 4);
        default:
            return '';
    }
}

/**
 * @param string|int|bool|null|object|array|float $value
 * @param int $depth
 * @return string
 */
function stringifyValue($value, int $depth): string
{
    if (!is_object($value)) {
        return toString($value);
    }
    $indent = str_repeat(" ", $depth * 4 + 4);
    $bracketIndent = str_repeat(" ", $depth * 4);
    $result = array_map(function ($key) use ($value, $depth, $indent): string {
        $stringifiedValue = stringifyValue($value->{$key}, $depth + 1);
        return "{$indent}{$key}: {$stringifiedValue}";
    }, array_keys((array)$value));
    return "{\n" . implode("\n", $result) . "\n{$bracketIndent}}";
}

function stylishDiffFormat(array $differenceTree, int $depth = 1): string
{
    $lines = array_map(function ($value) use ($depth): string {
        switch ($value['type']) {
            case 'parent':
                $children = stylishDiffFormat($value['children'], $depth + 1);
                return getIndent($depth) . getTypeIndent($value['type']) .
                    "{$value['key']}: " . $children;
            case 'modified':
                $oldValue = stringifyValue($value['oldValue'], $depth);
                $newValue = stringifyValue($value['newValue'], $depth);
                return getIndent($depth) . getTypeIndent('oldValue') . "{$value['key']}: $oldValue\n" .
                    getIndent($depth) . getTypeIndent('newValue') . "{$value['key']}: $newValue";
            default:
                $formattedValue = stringifyValue($value['value'], $depth);
                return getIndent($depth) . getTypeIndent($value['type']) . "{$value['key']}: $formattedValue";
        }
    }, $differenceTree);
    $result = implode("\n", $lines);
    return "{\n{$result}\n" . getIndent($depth, 'back') . "}";
}
