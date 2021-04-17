<?php

namespace Differ\Differ;

use Exception;

/**
 * @param string $formatterName
 * @param array $differenceTree
 * @return string
 * @throws Exception
 */
function formatDifference(string $formatterName, array $differenceTree): string
{
    switch ($formatterName) {
        case 'plain':
            $formatted = Plain\format($differenceTree);
            break;
        case 'json':
            $formatted = Json\format($differenceTree);
            break;
        case 'stylish':
            $formatted = Stylish\format($differenceTree);
            break;
        default:
            throw new Exception('Unknown format ' . $formatterName);
    }
    return $formatted;
}
