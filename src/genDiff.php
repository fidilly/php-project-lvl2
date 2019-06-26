<?php

namespace Differ\genDiff;
use Docopt;

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
    genDiff($pathToFile1, $pathToFile2);
           
}

function genDiff($pathToFile1, $pathToFile2)
{
    if (file_exists($pathToFile1) && file_exists($pathToFile2)) {
        $File1Content = json_decode(file_get_contents($pathToFile1));
        $File2Content = json_decode(file_get_contents($pathToFile2));
    }
}
