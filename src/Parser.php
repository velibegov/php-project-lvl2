<?php

namespace Differ\Differ;

use Exception;
use stdClass;
use Symfony\Component\Yaml\Yaml;

/**
 * @param string $data
 * @param string $dataType
 * @return stdClass
 * @throws Exception
 */
function parseData(string $data, string $dataType): stdClass
{
    switch ($dataType) {
        case 'json':
            $parsed = json_decode($data);
            break;
        case 'yml':
        case 'yaml':
            $parsed = Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
            break;
        default:
            throw new Exception("Unsupported data type $dataType");
    }
    return $parsed;
}
