<?php

namespace Differ\Differ;

use Docopt\Response;
use Symfony\Component\Yaml\Yaml;

function fileParse(string $filePath): \stdClass
{
    $parsed = '';
    $fileContent = file_get_contents($filePath) ? : '';
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    switch ($extension) {
        case 'json':
            $parsed = json_decode($fileContent);
            break;
        case 'yml':
        case 'yaml':
            $parsed = Yaml::parse($fileContent, Yaml::PARSE_OBJECT_FOR_MAP);
            break;
    }
    return $parsed;
}

function argumentsParse(Response $args): array
{
    return [
        'firstFilePath' => $args['<firstFile>'],
        'secondFilePath' => $args['<secondFile>'],
        'format' => $args['--format']
    ];
}
