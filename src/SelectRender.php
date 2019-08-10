<?php

namespace Differ\SelectRender;

function selectRender($ast, $format)
{
    $renderPath = "\Differ\Renderers\\$format\\render";
    return $renderPath($ast);
}
