<?php

namespace Differ\Renderers\plain;

function render($ast, $depth = 0, $keyAcc = '')
{
    return array_reduce($ast, function ($acc, $item) use ($depth, $keyAcc) {
        $text = $depth === 0 ? "Property '" : "Property '$keyAcc";
        $type = $item['type'];
        $key = $item['key'];
        
        switch ($type) {
            case 'nested':
                $after = render($item['after'], $depth + 1, $keyAcc . "$key.");
                return $acc . $after;
            case 'unchanged':
                return $acc;
            case 'removed':
                return $acc . $text . "$key' was $type\n";
            case 'added':
                if (is_array(boolToString($item['after']))) {
                    return $acc . $text . "$key' was $type with value: 'complex value'\n";
                }
                return $acc . $text . "$key' was $type with value: '" . boolToString($item['after']) . "'\n";
            case 'changed':
                $part = boolToString($item['before']) . "' to '" . boolToString($item['after']) . "'\n";
                return $acc . $text . "$key' was $type. From '" . $part;
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
