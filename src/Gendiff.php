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

    $content1 = validateContent($file1Content);
    $content2 = validateContent($file2Content);

    if (!is_null($content1) && !is_null($content2)) {
        $ast = makeDiffAst($content1, $content2);
        return selectRender($ast, $format);
    }
}

function getContent($pathToFile)
{
    if (file_exists($pathToFile)) {
        $fileContent = file_get_contents($pathToFile);
        return $fileContent;
    }
}

function validateContent($content)
{
    if (!is_null($content)) {
        try {
            return Yaml::parse($content);
        } catch (ParseException $exception) {
            return json_decode($content, true);
        }
    }
}
