<?php

namespace Php\Project\Lvl2;

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

function stringifyValue(mixed $value, int $depth): string
{
    if (!is_object($value)) {
        return toString($value);
    }
    $indent = str_repeat(" ", $depth * 4 + 4);
    $bracketIndent = str_repeat(" ", $depth * 4);
    $result = array_map(function ($key) use ($value, $depth, $indent) {
        $stringifiedValue = stringifyValue($value->{$key}, $depth + 1);
        return "{$indent}{$key}: {$stringifiedValue}";
    }, array_keys((array)$value));
    return "{\n" . implode("\n", $result) . "\n{$bracketIndent}}";
}

function stylishDiffFormat(array $differenceTree, int $depth = 1): string
{
    $baseIndent = " ";
    $currentIndent = str_repeat($baseIndent, $depth * 4 - 2);
    $bracketIndent = str_repeat($baseIndent, $depth * 4 - 4);

    $lines = array_map(function ($value) use ($currentIndent, $depth) {
        switch ($value['type']) {
            case 'parent':
                $children = stylishDiffFormat($value['children'], $depth + 1);
                return $currentIndent . getTypeIndent($value['type']) .
                    "{$value['key']}: " . $children;
            case 'modified':
                $oldValue = stringifyValue($value['oldValue'], $depth);
                $newValue = stringifyValue($value['newValue'], $depth);
                return $currentIndent . getTypeIndent('oldValue') . "{$value['key']}: $oldValue\n" .
                    $currentIndent . getTypeIndent('newValue') . "{$value['key']}: $newValue";
            default:
                $formattedValue = stringifyValue($value['value'], $depth);
                return $currentIndent . getTypeIndent($value['type']) . "{$value['key']}: $formattedValue";
        }
    }, $differenceTree);
    $result = implode("\n", $lines);
    return "{\n{$result}\n{$bracketIndent}}";
}
