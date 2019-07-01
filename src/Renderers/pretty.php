<?php

namespace Differ\Renderers\pretty;

function render($format, $depth, $data, $key, $before, $after)
{
    $tabs = "\n" . str_repeat('    ', $depth);
    if ($data === 'unchanged' || $data === 'nested') {
        return $tabs . "    $key: $after";
    } elseif ($data === 'changed') {
        return $tabs . "  + $key: $after" . $tabs . "  - $key: $before";
    } elseif ($data === 'added') {
        return $tabs . "  + $key: $after";
    } elseif ($data === 'removed') {
        return $tabs . "  - $key: $before";
    }
}

function getTags($format, $depth)
{
    $tabs = "\n" . str_repeat('    ', $depth);
    return ["{", "$tabs}"];
}
