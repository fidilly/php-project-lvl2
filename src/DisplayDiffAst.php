<?php

namespace Differ\DisplayDiffAst;

function displayDiffAst($ast, $format, $depth = 0)
{
    $render = "\Differ\Renderers\\$format\\render";
    if ($format === 'json') {
        return $render($ast);
    }
    $getTags = "\Differ\Renderers\\$format\\getTags";
    [$openTag, $closeTag] = $getTags($format, $depth);
    $rendering = array_reduce($ast, function ($acc, $item) use ($depth, $format, $render) {
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
        
        return $acc . $render($format, $depth, $data, $key, $before, $after);
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
