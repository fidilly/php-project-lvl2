<?php

namespace Differ\genDiff;
use Docopt;

function genDiff()
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
    foreach ($args as $k => $v) {
        echo $k . ': ' . json_encode($v) . PHP_EOL;
    }
}
