<?php

namespace Differ\Cli;
use Docopt;
use function Differ\Gendiff\gendiff;

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
    
    $args = Docopt::handle($doc, ['version' => 'gendiff 1.1.0']);
    $pathToFile1 = './' . $args['<firstFile>'];
    $pathToFile2 = './' . $args['<secondFile>'];
    $format = $args['--format'];
    echo gendiff($pathToFile1, $pathToFile2, $format) . PHP_EOL;
}
