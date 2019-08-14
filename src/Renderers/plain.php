<?php

namespace Differ\Renderers\plain;

function render($ast, $depth = 0, $keyAcc = '')
{
    $composeText = array_reduce($ast, function ($acc, $item) use ($depth, $keyAcc) {
        $text = $depth === 0 ? "Property '" : "Property '$keyAcc";
        $type = $item['type'];
        $key = $item['key'];
        
        switch ($type) {
            case 'nested':
                $acc[] = render($item['after'], $depth + 1, $keyAcc . "$key.");
                return $acc;
            case 'unchanged':
                return $acc;
            case 'removed':
                $acc[] = "$text$key' was $type";
                return $acc;
            case 'added':
                $after = stringify($item['after']);
                $acc[] = "$text$key' was $type with value: '$after'";
                return $acc;
            case 'changed':
                $before = stringify($item['before']);
                $after = stringify($item['after']);
                $acc[] = "$text$key' was $type. From '$before' to '$after'";
                return $acc;
        }
    }, []);
    return implode($composeText, "\n");
}

function stringify($value)
{
    if (!is_bool($value) && !is_array($value)) {
        return $value;
    } elseif (is_bool($value)) {
        return $value ? 'true' : 'false';
    } elseif (is_array($value)) {
        return 'complex value';
    }
}
