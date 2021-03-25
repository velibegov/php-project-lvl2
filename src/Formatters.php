<?php

namespace Differ\Differ;

use function Differ\Differ\Plain\plainDiffFormat;

function formatDifference(string $formatterName, array $differenceTree): string
{
    switch ($formatterName) {
        case 'plain':
            return plainDiffFormat($differenceTree);
        case 'json':
            return jsonDiffFormat($differenceTree);
        case 'stylish':
            return stylishDiffFormat($differenceTree);
        default:
            throw new \Exception('Unknown format ' . $formatterName);
    }
}
