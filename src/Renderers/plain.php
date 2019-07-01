<?php

namespace Differ\Renderers\plain;

function render($format, $depth, $data, $key, $before, $after)
{
    $text = $depth === 0 ? "Property '" : '';
    if ($data == 'nested') {
        $filtered = array_filter(explode("\n", $after), function ($item) {
            return !empty($item);
        });
        $glue = implode("\n", array_map(function ($item) use ($text, $key) {
            return $text . "$key." . $item;
        }, $filtered));
        return $glue . PHP_EOL;
    } elseif ($data == "unchanged") {
        return;
    } elseif ($data == 'changed') {
        return $text . "$key' was $data. From '$before' to '$after'\n";
    } elseif ($data == 'removed') {
        return $text . "$key' was $data\n";
    } elseif ($data == 'added') {
        if ($after == '') {
            return $text . "$key' was $data with value: 'complex value'\n";
        }
        return $text . "$key' was $data with value: '$after'\n";
    }
}

function getTags($format, $depth)
{
    return[null, null];
}
