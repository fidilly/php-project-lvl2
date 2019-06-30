<?php

namespace Differ;
use Docopt;
use function Funct\Collection\union;
use function Differ\makeDiffAst\makeDiffAst;
use function Differ\displayDiffAst\displayDiffAst;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

function run()
{
    $doc = <<<DOC
Generate diff

Usage:
    gendiff (-h|--help)
    gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
    -h --help         Show this screen.
    --format <fmt>    Report format [default: pretty].
    -v --version      Show version.
DOC;
    
    $args = Docopt::handle($doc, ['version' => 'gendiff 1.0.0']);
    $pathToFile1 = './' . $args['<firstFile>'];
    $pathToFile2 = './' . $args['<secondFile>'];
    $format = $args['--format'];
    echo genDiff($pathToFile1, $pathToFile2, $format) . PHP_EOL;
}

function gendiff($pathToFile1, $pathToFile2, $format)
{
    $content = getContents($pathToFile1, $pathToFile2);
    if (!is_null($content)) {
        [$file1Content, $file2Content] = $content;
    } else {
        return;
    }

    $ast = makeDiffAst($file1Content, $file2Content);
    return displayDiffAst($ast, $format);
}

function getContents($pathToFile1, $pathToFile2)
{
    if (file_exists($pathToFile1) && file_exists($pathToFile2)) {
        $jsonContent1 = json_decode(file_get_contents($pathToFile1), true);
        if (json_last_error() === 0) {
            $jsonContent2 = json_decode(file_get_contents($pathToFile2), true);
            if (json_last_error() === 0) {
                $file1Content = $jsonContent1;
                $file2Content = $jsonContent2;
                return [$file1Content, $file2Content];
            }
        } else {
            try {
                $file1Content = Yaml::parseFile($pathToFile1);
                $file2Content = Yaml::parseFile($pathToFile2);
                return [$file1Content, $file2Content];
            } catch (ParseException $exception) {
            }
        }
    }
}
