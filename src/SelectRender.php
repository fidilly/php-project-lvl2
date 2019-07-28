<?php

namespace Differ\SelectRender;

function selectRender($ast, $format)
{
    $renderPath = "\Differ\Renderers\\$format\\render";
    if (function_exists($renderPath)) {
        return $renderPath($ast);
    } else {
        return \Differ\Renderers\pretty\render($ast);
    }
}
