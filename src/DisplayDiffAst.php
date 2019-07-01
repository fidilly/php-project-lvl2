<?php

namespace Differ\DisplayDiffAst;

use function Differ\MakeDiffAst\makeDiffAst;

function displayDiffAst($ast, $format, $depth = 0)
{
    [$openTag, $closeTag] = getTags($format, $depth);
    $rendering = array_reduce($ast, function ($acc, $item) use ($depth, $format) {
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

        return $acc . makeDiffFormat($format, $depth, $data, $key, $before, $after);
    }, "");
    return "$openTag" . $rendering . "$closeTag";
}

function boolToString($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    } else {
        return $value;
    }
}

function makeDiffFormat($format, $depth, $data, $key, $before, $after)
{
    if ($format === 'plain') {
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
    } else {
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
}

function getTags($format, $depth)
{
    switch ($format) {
        case 'plain':
            return[null, null];
        default:
            $tabs = "\n" . str_repeat('    ', $depth);
            return ["{", "$tabs}"];
    }
}
