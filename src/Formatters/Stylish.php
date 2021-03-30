<?php

namespace Differ\Differ\Stylish;

define('INDENT', 'indent');
define('BRACKET_INDENT', 'bracketIndent');
define('INNER_INDENT', 'innerIndent');
define('INNER_BRACKET_INDENT', 'innerBracketIndent');
define('BASE_INDENT', ' ');

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
        case 'new':
        case 'added':
            return '+ ';
        case 'old':
        case 'removed':
            return '- ';
        default:
            return '  ';
    }
}

function getIndent(int $depth, string $position = INDENT): string
{
    switch ($position) {
        case INDENT:
            return str_repeat(BASE_INDENT, $depth * 4 - 2);
        case BRACKET_INDENT:
            return str_repeat(BASE_INDENT, $depth * 4 - 4);
        case INNER_INDENT:
            return str_repeat(BASE_INDENT, $depth * 4 + 4);
        case INNER_BRACKET_INDENT:
            return str_repeat(BASE_INDENT, $depth * 4);
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
    $result = array_map(function ($key) use ($value, $depth): string {
        $stringifiedValue = stringifyValue($value->{$key}, $depth + 1);
        return getIndent($depth, INNER_INDENT) . "{$key}: {$stringifiedValue}";
    }, array_keys((array)$value));
        return "{\n" . implode("\n", $result) . "\n" . getIndent($depth, INNER_BRACKET_INDENT) . "}";
}

function format(array $differenceTree, int $depth = 1): string
{
    $lines = array_map(function ($value) use ($depth): string {
        switch ($value['type']) {
            case 'parent':
                $children = format($value['children'], $depth + 1);
                return getIndent($depth) . getTypeIndent($value['type']) .
                    "{$value['key']}: " . $children;
            case 'modified':
                $oldValue = stringifyValue($value['old'], $depth);
                $newValue = stringifyValue($value['new'], $depth);
                return getIndent($depth) . getTypeIndent('old') . "{$value['key']}: $oldValue\n" .
                    getIndent($depth) . getTypeIndent('new') . "{$value['key']}: $newValue";
            default:
                $formattedValue = stringifyValue($value['value'], $depth);
                return getIndent($depth) . getTypeIndent($value['type']) . "{$value['key']}: $formattedValue";
        }
    }, $differenceTree);
    $result = implode("\n", $lines);
    return "{\n{$result}\n" . getIndent($depth, BRACKET_INDENT) . "}";
}
