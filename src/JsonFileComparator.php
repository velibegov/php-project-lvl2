<?php

namespace Php\Project\Lvl2;

use Docopt\Response;

function run(Response $args): string
{
    $firstFilePath = $args['<firstFile>'];
    $secondFilePath = $args['<secondFile>'];
    return genDiff($firstFilePath, $secondFilePath);
}

function genDiff(string $firstFilePath, string $secondFilePath): string
{
    $firstFileContent = file_get_contents($firstFilePath);
    $secondFileContent = file_get_contents($secondFilePath);

    $firstFileContentDecoded = json_decode($firstFileContent, true);
    $secondFileContentDecoded = json_decode($secondFileContent, true);

    $intersections = array_uintersect_uassoc(
        $firstFileContentDecoded, $secondFileContentDecoded, fn($a, $b) => $a <=> $b, fn($a, $b) => $a <=> $b);

    $firstDiffSecond = array_udiff_uassoc(
        $firstFileContentDecoded, $secondFileContentDecoded, fn($a, $b) => $a <=> $b, fn($a, $b) => $a <=> $b);

    $secondDiffFirst = array_udiff_uassoc(
        $secondFileContentDecoded, $firstFileContentDecoded, fn($a, $b) => $a <=> $b, fn($a, $b) => $a <=> $b);

    $changed = [];
    array_walk($firstDiffSecond, function ($value, $key) use (&$changed) {
        $changed += ["- ${key}" => $value];
    });

    $added = [];
    array_walk($secondDiffFirst, function ($value, $key) use (&$added) {
        $added += ["+ ${key}" => $value];
    });

    $diffData = array_merge($intersections, $changed, $added);

    uksort($diffData, function ($a, $b) {
        if ($a[0] === '-' || $a[0] === '+') {
            $a = mb_substr($a, 2, strlen($a));
        }
        if ($b[0] === '-' || $b[0] === '+') {
            $b = mb_substr($b, 2, strlen($b));
        }
        return $a <=> $b;
    });

    $result = '';
    array_walk($diffData, function ($value, $key) use (&$result) {
        if (is_bool($value)) {
            $value == ''? $value = 'false' : $value = 'true';
        }
        if ($key[0] != '-' && $key[0] != '+') {
            $result .= "  ${key}: ${value}" . PHP_EOL;
        } else {
            $result .= "${key}: ${value}" . PHP_EOL;
        }
    });
    return "{" . PHP_EOL . $result . "}" . PHP_EOL;
}


/*print_r(genDiff(
    'C:\Projects\php-project-lvl2\tests\fixtures\file1.json',
    'C:\Projects\php-project-lvl2\tests\fixtures\file2.json'));
print_r(genDiff(
    'C:\Projects\php-project-lvl2\tests\fixtures\file2.json',
    'C:\Projects\php-project-lvl2\tests\fixtures\file1.json'));*/