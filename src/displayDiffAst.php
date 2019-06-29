<?php

namespace Differ\displayDiffAst;

use function Differ\makeDiffAst\makeDiffAst;

function displayDiffAst($ast)
{
    $rendering = array_reduce($ast, function ($acc, $item) {
        $data = $item['data'];
        $key = $item['key'];
        $before = $item['before'];
        $after = $item['after'];
        $afterText = "$key: $after";
        $beforeText = "$key: $before";
        
        if ($data === 'unchanged') {
            return $acc . "    $afterText\n";
        } elseif ($data === 'changed') {
            return $acc . "  + $afterText\n" . "  - $beforeText\n";
        } elseif ($data === 'added') {
            return $acc . "  + $afterText\n";
        } elseif ($data === 'removed') {
            return $acc . "  - $beforeText\n";
        }
    }, "{\n") . "}\n";

    return $rendering;
}
