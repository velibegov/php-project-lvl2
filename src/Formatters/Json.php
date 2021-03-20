<?php

namespace Php\Project\Lvl2;

function jsonDiffFormat(array $differenceTree): string
{
    return json_encode($differenceTree) ?: '';
}
