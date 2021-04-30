<?php

namespace Differ\Differ\Stylish;

use Exception;

const BASE_INDENT = ' ';
const BIG_INDENT = 'bigIndent';
const SMALL_INDENT = 'smallIndent';

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

/**
 * @param int $depth
 * @param string $indentModifier
 * @return string
 * @throws Exception
 */
function getIndent(int $depth, string $indentModifier = SMALL_INDENT): string
{
    switch ($indentModifier) {
        case BIG_INDENT:
            $indent = str_repeat(BASE_INDENT, $depth * 4);
            break;
        case SMALL_INDENT:
            $indent = str_repeat(BASE_INDENT, $depth * 4 - 2);
            break;
        default:
            throw new Exception("Unsupported indent modifier $indentModifier");
    }
    return $indent;
}

/**
 * @param string|int|bool|null|object|array|float $value
 * @param int $depth
 * @return string
 * @throws Exception
 */
function stringifyValue($value, int $depth): string
{
    if (!is_object($value)) {
        return toString($value);
    }
    $result = array_map(function ($key) use ($value, $depth): string {
        $stringifiedValue = stringifyValue($value->{$key}, $depth + 1);
        return getIndent($depth + 1, BIG_INDENT) . "{$key}: {$stringifiedValue}";
    }, array_keys((array)$value));
    return "{\n" . implode("\n", $result) . "\n" . getIndent($depth, BIG_INDENT) . "}";
}

/**
 * @param array $differenceTree
 * @param int $depth
 * @return string
 * @throws Exception
 */
function format(array $differenceTree, int $depth = 1): string
{
    $lines = array_map(function ($value) use ($depth): string {
        switch ($value['type']) {
            case 'parent':
                $children = format($value['children'], $depth + 1);
                return getIndent($depth) . '  ' .
                    "{$value['key']}: " . $children;
            case 'modified':
                $oldValue = stringifyValue($value['old'], $depth);
                $newValue = stringifyValue($value['new'], $depth);
                $oldLine = getIndent($depth) . '- ' . "{$value['key']}: $oldValue";
                $newLine = getIndent($depth) . '+ ' . "{$value['key']}: $newValue";
                return $oldLine . "\n" . $newLine;
            case 'unmodified':
                $formattedValue = stringifyValue($value['value'], $depth);
                return getIndent($depth, BIG_INDENT) . "{$value['key']}: $formattedValue";
            case 'added':
                $formattedValue = stringifyValue($value['value'], $depth);
                return getIndent($depth) . '+ ' . "{$value['key']}: $formattedValue";
            case 'removed':
                $formattedValue = stringifyValue($value['value'], $depth);
                return getIndent($depth) . '- ' . "{$value['key']}: $formattedValue";
            default:
                throw new Exception("Unsupported value type {$value['type']}");
        }
    }, $differenceTree);
    $result = implode("\n", $lines);
    return "{\n{$result}\n" . getIndent($depth - 1, BIG_INDENT) . "}";
}
