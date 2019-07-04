<?php

namespace Differ\Renderers\plain;

function render($format, $depth, $type, $key, $before, $after)
{
    $text = $depth === 0 ? "Property '" : '';
    if ($type == 'nested') {
        $filtered = array_filter(explode("\n", $after), function ($item) {
            return !empty($item);
        });
        $glue = implode("\n", array_map(function ($item) use ($text, $key) {
            return $text . "$key." . $item;
        }, $filtered));
        return $glue . PHP_EOL;
    } elseif ($type == "unchanged") {
        return;
    } elseif ($type == 'changed') {
        return $text . "$key' was $type. From '$before' to '$after'\n";
    } elseif ($type == 'removed') {
        return $text . "$key' was $type\n";
    } elseif ($type == 'added') {
        if ($after == '') {
            return $text . "$key' was $type with value: 'complex value'\n";
        }
        return $text . "$key' was $type with value: '$after'\n";
    }
}

function getTags($format, $depth)
{
    return[null, null];
}
