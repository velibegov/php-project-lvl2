<?php

namespace Differ\Differ;

use Symfony\Component\Yaml\Yaml;

/**
 * @param string $filePath
 * @return bool|string
 * @throws \Exception
 */
function fileRead(string $filePath)
{
    if (file_exists($filePath)) {
        return file_get_contents($filePath);
    } else {
        throw new \Exception('File not exists ' . $filePath);
    }
}

function fileParse(string $filePath): \stdClass
{
    $fileContent = fileRead($filePath);
    if (is_string($fileContent)) {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'json':
                $parsed = json_decode($fileContent);
                break;
            case 'yml':
            case 'yaml':
                $parsed = Yaml::parse($fileContent, Yaml::PARSE_OBJECT_FOR_MAP);
                break;
            default:
                throw new \Error('Unsupported file extension ' . $extension);
        }
    } else {
        throw new \Exception('Unknown file content');
    }
    return $parsed;
}
