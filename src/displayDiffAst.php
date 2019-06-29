<?php

namespace Differ\displayDiffAst;

use function Differ\makeDiffAst\makeDiffAst;

function displayDiffAst($ast)
{
    $rendering = array_reduce($ast, function ($acc, $item) {
        $data = $item['data'];
        $key = $item['key'];
        $before = boolToString($item['before']);
        $after = boolToString($item['after']);
        
        if ($data === 'unchanged') {
            return $acc . "    $key: $after\n";
        } elseif ($data === 'changed') {
            return $acc . "  + $key: $after\n" . "  - $key: $before\n";
        } elseif ($data === 'added') {
            return $acc . "  + $key: $after\n";
        } elseif ($data === 'removed') {
            return $acc . "  - $key: $before\n";
        }
    }, "{\n") . "}\n";

    return $rendering;
}

function boolToString($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    } else {
        return $value;
    }
}