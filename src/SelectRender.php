<?php

namespace Differ\SelectRender;

function selectRender($ast, $format)
{
    if ($format === 'pretty') {
        return \Differ\Renderers\pretty\render($ast);
    } elseif ($format === 'plain') {
        return \Differ\Renderers\plain\render($ast);
    } elseif ($format === 'json') {
        return \Differ\Renderers\json\render($ast);
    } else {
        return \Differ\Renderers\pretty\render($ast);
    }
}
