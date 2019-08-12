<?php

namespace Differ\MakeDiffAst;

use function Differ\getContents;
use function Funct\Collection\union;

function makeDiffAst($contentBeforeChange, $contentAfterChange)
{
    $allKeys = union(array_keys($contentBeforeChange), array_keys($contentAfterChange));
    $makeDiff = function ($acc, $key) use ($contentBeforeChange, $contentAfterChange) {
        if (array_key_exists($key, $contentBeforeChange) && !array_key_exists($key, $contentAfterChange)) {
            $acc[] = ['type' => 'removed',
                      'key' => $key,
                      'before' => $contentBeforeChange[$key]];
            return $acc;
        } elseif (array_key_exists($key, $contentAfterChange) && !array_key_exists($key, $contentBeforeChange)) {
            $acc[] = ['type' => 'added',
                      'key' => $key,
                      'after' => $contentAfterChange[$key]];
            return $acc;
        } elseif (array_key_exists($key, $contentAfterChange) && array_key_exists($key, $contentBeforeChange)) {
            $before = $contentBeforeChange[$key];
            $after = $contentAfterChange[$key];
            if (is_array($before) && is_array($after)) {
                $acc[] = ['type' => 'nested',
                          'key' => $key,
                          'after' => makeDiffAst($before, $after)];
                return $acc;
            } else {
                $type = ($before === $after) ? 'unchanged' : 'changed';
                $acc[] = ['type' => $type,
                          'key' => $key,
                          'before' => $before,
                          'after' => $after];
                return $acc;
            }
        }
    };

    $diffAst = array_reduce($allKeys, $makeDiff, []);
    return $diffAst;
}
