<?php

namespace Differ\displayDiffAst;

use function Differ\makeDiffAst\makeDiffAst;

function displayDiffAst($ast, $format, $depth = 0)
{
    $tabs = "\n" . str_repeat('    ', $depth);
    $rendering = array_reduce($ast, function ($acc, $item) use ($tabs, $depth, $format) {
        $data = $item['data'];
        $key = $item['key'];
        if (is_array($item['before'])) {
            $before = displayDiffAst($item['before'], $format, $depth + 1);
        } else {
            $before = boolToString($item['before']);
        }

        if (is_array($item['after'])) {
            $after = displayDiffAst($item['after'], $format, $depth + 1);
        } else {
            $after = boolToString($item['after']);
        }
        
        if ($data === 'unchanged') {
            return $acc . $tabs . "    $key: $after";
        } elseif ($data === 'changed') {
            return $acc . $tabs . "  + $key: $after" . $tabs . "  - $key: $before";
        } elseif ($data === 'added') {
            return $acc . $tabs . "  + $key: $after";
        } elseif ($data === 'removed') {
            return $acc . $tabs . "  - $key: $before";
        }
    }, "{") . "$tabs}";
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
