<?php

namespace Differ\Gendiff;

use function Differ\MakeDiffAst\makeDiffAst;
use function Differ\Renderers\pretty\render;
use function Differ\SelectRender\selectRender;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

function gendiff($pathToFile1, $pathToFile2, $format)
{
    $file1Content = getContent($pathToFile1);
    $file2Content = getContent($pathToFile2);
    if (!is_null($file1Content) && !is_null($file2Content)) {
        $ast = makeDiffAst($file1Content, $file2Content);
    } else {
        return;
    }

    return selectRender($ast, $format);
}

function getContent($pathToFile)
{
    if (file_exists($pathToFile)) {
        if (isJsonFile($pathToFile)) {
            $fileContent = json_decode(file_get_contents($pathToFile), true);
            return $fileContent;
        } else {
            try {
                $fileContent = Yaml::parseFile($pathToFile);
                return $fileContent;
            } catch (ParseException $exception) {
            }
        }
    }
}

function isJsonFile($pathToFile)
{
    $fileContent = json_decode(file_get_contents($pathToFile));
    if (json_last_error() === 0) {
            return true;
    }
    return false;
}
