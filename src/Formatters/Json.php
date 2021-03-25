<?php

namespace Differ\Differ;

function jsonDiffFormat(array $differenceTree): string
{
    if (is_string(json_encode($differenceTree))) {
        return json_encode($differenceTree);
    } else {
        throw new \Exception('Impossible to encode into JSON');
    }
}
