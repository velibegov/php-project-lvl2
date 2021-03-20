<?php

namespace Differ\Differ;

use function Differ\Differ\Plain\plainDiffFormat;

function getFormatter(string $formatterName, array $differenceTree): string
{
    switch ($formatterName) {
        case 'plain':
            return plainDiffFormat($differenceTree);
        case 'json':
            return jsonDiffFormat($differenceTree);
        default:
            return stylishDiffFormat($differenceTree);
    }
}
