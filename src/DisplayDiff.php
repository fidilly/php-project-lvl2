<?php

namespace Differ\DisplayDiff;


function displayDiff($ast, $format, $depth = 0)
{
    $tabs = "\n" . str_repeat('    ', $depth);
    $rendering = array_reduce($ast, function ($acc, $item) use ($tabs, $depth, $format) {
        $type = $item['type'];
        $key = $item['key'];
        $before = $item['before'];
        $after = $item['after'];

        if ($type === 'nested') {
            $after = displayDiff($item['after'], $format, $depth + 1);
            return $acc . $tabs . "    $key: $after";
        } elseif ($type === 'unchanged') {
            $after = renderValue($after, $depth);
            return $acc . $tabs . "    $key: $after";
        } elseif ($type === 'changed') {
            $before = renderValue($before, $depth);
            $after = renderValue($after, $depth);
            return $acc . $tabs . "  + $key: $after" . $tabs . "  - $key: $before";
        } elseif ($type === 'added') {
            $after = renderValue($after, $depth);
            return $acc . $tabs . "  + $key: $after";
        } elseif ($type === 'removed') {
            $before = renderValue($before, $depth);
            return $acc . $tabs . "  - $key: $before";
        }
    }, "{") . "$tabs}";
    return $rendering;
}

function renderValue ($value, $depth)
{
    if (is_array($value)) {
        $tabs = "\n" . str_repeat('    ', $depth + 1);
        $array = $value;
        $reduced = array_reduce(array_keys($array), function ($acc, $key) use ($array, $tabs) {
            if (is_array($array[$key])) {
                return renderValue($array[$key], $depth + 1);
            } else {
                return $acc . $tabs . "    $key: " . boolToString($array[$key]);
            }
        }, "{"); 
        return $reduced . "$tabs}";
    } else {
        return boolToString($value);
    } 
}

function boolToString($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    } else {
        return $value;
    }
}
