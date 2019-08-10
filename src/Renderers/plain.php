<?php

namespace Differ\Renderers\plain;

function render($ast, $depth = 0, $keyAcc = '')
{
    return array_reduce($ast, function ($acc, $item) use ($depth, $keyAcc) {
        $text = $depth === 0 ? "Property '" : "Property '$keyAcc";
        $type = $item['type'];
        $key = $item['key'];

        if ($type === 'nested') {
            $after = render($item['after'], $depth + 1, $keyAcc . "$key.");
            return $acc . $after;
        } elseif ($type === 'unchanged') {
            return $acc;
        } elseif ($type === 'removed') {
            return $acc . $text . "$key' was $type\n";
        } elseif ($type === 'added') {
            if (is_array(boolToString($item['after']))) {
                return $acc . $text . "$key' was $type with value: 'complex value'\n";
            }
            return $acc . $text . "$key' was $type with value: '" . boolToString($item['after']) . "'\n";
        } elseif ($type === 'changed') {
            return $acc . $text . "$key' was $type. From '" . boolToString($item['before']) . "' to '" . boolToString($item['after']) . "'\n";
        }
    }, "");
}

function boolToString($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    } else {
        return $value;
    }
}
