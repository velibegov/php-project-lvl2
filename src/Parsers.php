<?php

namespace Differ\Differ;

use Exception;
use Symfony\Component\Yaml\Yaml;

/**
 * @param string $filePath
 * @return string
 * @throws Exception
 */
function fileRead(string $filePath): string
{
    if (file_exists($filePath)) {
        $data = file_get_contents($filePath);
    } else {
        throw new Exception('File not exists ' . $filePath);
    }
    if (is_string($data)) {
        return $data;
    } else {
        throw new Exception('Incorrect file content ' . $filePath);
    }
}

function dataParse(string $data, string $extension): \stdClass
{
    switch ($extension) {
        case 'json':
            $parsed = json_decode($data);
            break;
        case 'yml':
        case 'yaml':
            $parsed = Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
            break;
        default:
            throw new \Error('Unsupported file extension ' . $extension);
    }
    return $parsed;
}

function getContent(string $filePath): \stdClass
{
    $data = fileRead($filePath);
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    return dataParse($data, $extension);
}
