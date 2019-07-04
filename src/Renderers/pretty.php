<?php

namespace Differ\Renderers\pretty;

function render($format, $depth, $type, $key, $before, $after)
{
    $tabs = "\n" . str_repeat('    ', $depth);
    if ($type === 'unchanged' || $type === 'nested') {
        return $tabs . "    $key: $after";
    } elseif ($type === 'changed') {
        return $tabs . "  + $key: $after" . $tabs . "  - $key: $before";
    } elseif ($type === 'added') {
        return $tabs . "  + $key: $after";
    } elseif ($type === 'removed') {
        return $tabs . "  - $key: $before";
    }
}

function getTags($format, $depth)
{
    $tabs = "\n" . str_repeat('    ', $depth);
    return ["{", "$tabs}"];
}
