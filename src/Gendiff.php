<?php

namespace Differ\Gendiff;

use function Differ\MakeDiffAst\makeDiffAst;
use function Differ\DisplayDiff\displayDiff;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

function gendiff($pathToFile1, $pathToFile2, $format)
{
    $content = getContents($pathToFile1, $pathToFile2);
    if (!is_null($content)) {
        [$file1Content, $file2Content] = $content;
    } else {
        return;
    }

    $ast = makeDiffAst($file1Content, $file2Content);
    return displayDiff($ast, $format);
}

function getContents($pathToFile1, $pathToFile2)
{
    if (isJsonFiles($pathToFile1, $pathToFile2)) {
        $file1Content = json_decode(file_get_contents($pathToFile1), true);
        $file2Content = json_decode(file_get_contents($pathToFile2), true);
        return [$file1Content, $file2Content];
    } else {
        try {
            $file1Content = Yaml::parseFile($pathToFile1);
            $file2Content = Yaml::parseFile($pathToFile2);
            return [$file1Content, $file2Content];
        } catch (ParseException $exception) {
            echo 'Invalid file path';
        }
    }
}

function isJsonFiles($pathToFile1, $pathToFile2)
{
    if (file_exists($pathToFile1) && file_exists($pathToFile2)) {
        $file1Content = json_decode(file_get_contents($pathToFile1));
        if (json_last_error() === 0) {
            $file2Content = json_decode(file_get_contents($pathToFile2));
            if (json_last_error() === 0) {
                return true;
            }
        }
    }
    return false;
}
