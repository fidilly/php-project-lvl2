<?php


namespace Differ\Renderers\pretty;


function render($ast, $depth = 0)
{
    $tabs = str_repeat('    ', $depth);
    $composeText = array_reduce($ast, function ($acc, $item) use ($tabs, $depth) {
        $key = $item['key'];
        switch ($item['type']) {
            case 'nested':
                $after = render($item['after'], $depth + 1);
                $acc[] = "$tabs    $key: $after";
                return $acc;
            case 'unchanged':
                $after = renderValue($item['after'], $depth);
                $acc[] = "$tabs    $key: $after";
                return $acc;
            case 'changed':
                $before = renderValue($item['before'], $depth);
                $after = renderValue($item['after'], $depth);
                $acc[] = "$tabs  + $key: $after";
                $acc[] = "$tabs  - $key: $before";
                return $acc;
            case 'added':
                $after = renderValue($item['after'], $depth);
                $acc[] = "$tabs  + $key: $after";
                return $acc;
            case 'removed':
                $before = renderValue($item['before'], $depth);
                $acc[] = "$tabs  - $key: $before";
                return $acc;
        }
    }, ["{"]);
    return implode($composeText, "\n") . "\n$tabs}";
}

function renderValue($value, $depth)
{
    if (!is_array($value)) {
        return boolToString($value);
    }

    $tabs = "\n" . str_repeat('    ', $depth + 1);
    $array = $value;
    $composeText = array_reduce(array_keys($array), function ($acc, $key) use ($array, $tabs, $depth) {
        if (is_array($array[$key])) {
            $acc[] = renderValue($array[$key], $depth + 1);
            return $acc;
        } else {
            $acc[] = "$tabs    $key: " . boolToString($array[$key]);
            return $acc;
        }
    }, []);
    return "{" . implode($composeText, "\n") . "$tabs}";
}

function boolToString($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    } else {
        return $value;
    }
}
