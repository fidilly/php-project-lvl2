<?php

namespace Differ\Renderers\json;

function render($ast)
{
    return json_encode($ast);
}
