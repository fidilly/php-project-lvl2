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

    $file1DataType = getDataType($pathToFile1);
    $file2DataType = getDataType($pathToFile2);

    $content1 = parseContent($file1Content, $file1DataType);
    $content2 = parseContent($file2Content, $file2DataType);

    if (is_null($content1) || is_null($content2)) {
        throw new \Exception("Wrong file format");
    }
    $ast = makeDiffAst($content1, $content2);
    return selectRender($ast, $format);
}

function getContent($pathToFile)
{
    if (!file_exists($pathToFile)) {
        throw new \Exception('File not found');
    }
    $fileContent = file_get_contents($pathToFile);
    return $fileContent;
}

function parseContent($content, $dataType)
{
    switch ($dataType) {
        case 'json':
            return json_decode($content, true);
        case 'yaml':
            return Yaml::parse($content);
        case 'yml':
            return Yaml::parse($content);
    }
}

function getDataType($pathToFile)
{
    return pathinfo($pathToFile, PATHINFO_EXTENSION);
}
