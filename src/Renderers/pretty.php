<?php


namespace Differ\Renderers\pretty;


function render($ast, $depth = 0)
{
    $tabs = "\n" . str_repeat('    ', $depth);
    return array_reduce($ast, function ($acc, $item) use ($tabs, $depth) {
        $type = $item['type'];
        $key = $item['key'];

        switch ($type) {
            case 'nested':
                $after = render($item['after'], $depth + 1);
                return $acc . $tabs . "    $key: $after";
            case 'unchanged':
                $after = renderValue($item['after'], $depth);
                return $acc . $tabs . "    $key: $after";
            case 'changed':
                $before = renderValue($item['before'], $depth);
                $after = renderValue($item['after'], $depth);
                return $acc . $tabs . "  + $key: $after" . $tabs . "  - $key: $before";
            case 'added':
                $after = renderValue($item['after'], $depth);
                return $acc . $tabs . "  + $key: $after";
            case 'removed':
                $before = renderValue($item['before'], $depth);
                return $acc . $tabs . "  - $key: $before";
        }
    }, "{") . "$tabs}";
}

function renderValue($value, $depth)
{
    if (!is_array($value)) {
        return boolToString($value);
    }

    $tabs = "\n" . str_repeat('    ', $depth + 1);
    $array = $value;
    return array_reduce(array_keys($array), function ($acc, $key) use ($array, $tabs, $depth) {
        if (is_array($array[$key])) {
            return renderValue($array[$key], $depth + 1);
        } else {
            return $acc . $tabs . "    $key: " . boolToString($array[$key]);
        }
    }, "{") . "$tabs}";
}

function boolToString($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    } else {
        return $value;
    }
}
