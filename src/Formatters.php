<?php

namespace Php\Project\Lvl2;

use function Php\Project\Lvl2\Plain\plainDiffFormat;

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
