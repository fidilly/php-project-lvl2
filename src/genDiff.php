<?php

namespace Differ;
use Docopt;
use function Funct\Collection\union;
use function Funct\Collection\flatten;
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
    
    $args = Docopt::handle($doc, ['version' => 'gendiff 0.0.1']);
    $pathToFile1 = './' . $args['<firstFile>'];
    $pathToFile2 = './' . $args['<secondFile>'];
    echo genDiff($pathToFile1, $pathToFile2);
}

function gendiff($pathToFile1, $pathToFile2)
{
    $content = getContents($pathToFile1, $pathToFile2);
    if (!is_null($content)) {
        [$file1Content, $file2Content] = $content;
    } else {
        return;
    }
    ###Этот блок будет удален
    $combCont = union($file1Content, $file2Content);
    $diffCont = array_diff_key($file1Content, $file2Content);
    $differ = array_reduce(array_keys($combCont), function ($acc, $key) use ($combCont, $diffCont, $file1Content) {
        if (array_key_exists($key, $file1Content) && !array_key_exists($key, $diffCont)) {
            $combContValue = boolToString($combCont[$key]);
            $file1Value = boolToString($file1Content[$key]);
            if ($combContValue === $file1Value) {
                return $acc . "    $key: $combContValue\n";
            } else {
                return $acc . "  + $key: $combContValue\n  - $key: $file1Value\n";
            }
        } elseif (array_key_exists($key, $diffCont)) {
            $diffContValue = boolToString($diffCont[$key]);
            return $acc . "  - $key: $diffContValue\n";
        } else {
            $combContValue = boolToString($combCont[$key]);
            return $acc . "  + $key: $combContValue\n";
        }
    }, "{\n") . "}\n";
    return $differ;
}
    ###Этот блок будет удален

function boolToString($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    } else {
        return $value;
    }
}

function getContents($pathToFile1, $pathToFile2)
{
    if (file_exists($pathToFile1) && file_exists($pathToFile2)) {
        $jsonContent1 = json_decode(file_get_contents($pathToFile1), true);
        if (json_last_error() === 0) {
            $jsonContent2 = json_decode(file_get_contents($pathToFile2), true);
            if (json_last_error() === 0) {
                $file1Content = $jsonContent1;
                print_r($file1Content);
                $file2Content = $jsonContent2;
                print_r($file2Content);
                $diffCont1 = array_diff_key($file1Content, $file2Content);
                print_r($diffCont1);
                $diffCont2 = array_diff_key($file2Content, $file1Content);
                print_r($diffCont2);
                $combCont = array_merge($file1Content, $diffCont2);
                print_r($combCont);
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
