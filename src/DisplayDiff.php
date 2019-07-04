<?php

namespace Differ\DisplayDiff;

function displayDiff($ast, $format, $depth = 0)
{
    $render = "\Differ\Renderers\\$format\\render";
    if ($format === 'json') {
        return $render($ast);
    }
    $getTags = "\Differ\Renderers\\$format\\getTags";
    [$openTag, $closeTag] = $getTags($format, $depth);
    $rendering = array_reduce($ast, function ($acc, $item) use ($depth, $format, $render) {
        $type = $item['type'];
        $key = $item['key'];
        if (is_array($item['before'])) {
            $before = displayDiff($item['before'], $format, $depth + 1);
        } else {
            $before = boolToString($item['before']);
        }

        if (is_array($item['after'])) {
            $after = displayDiff($item['after'], $format, $depth + 1);
        } else {
            $after = boolToString($item['after']);
        }
        
        return $acc . $render($format, $depth, $type, $key, $before, $after);
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
