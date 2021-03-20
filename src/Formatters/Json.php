<?php

namespace Differ;

function jsonDiffFormat(array $differenceTree): string
{
    return json_encode($differenceTree) ?: '';
}
