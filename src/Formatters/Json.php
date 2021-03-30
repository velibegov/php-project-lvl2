<?php

namespace Differ\Differ\Json;

function format(array $differenceTree): string
{
    $encoded = json_encode($differenceTree);
    if (is_string($encoded)) {
        return $encoded;
    } else {
        throw new \Exception('Impossible to encode into JSON');
    }
}
