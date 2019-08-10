<?php

namespace Differ\MakeDiffAst;

use function Differ\getContents;
use function Funct\Collection\union;

function makeDiffAst($contentBeforeChange, $contentAfterChange)
{
    $removedContent = array_diff_key($contentBeforeChange, $contentAfterChange);
    $addedContent = array_diff_key($contentAfterChange, $contentBeforeChange);
    $allKeys = union(array_keys($contentBeforeChange), array_keys($addedContent));

    $makeDiff = function ($acc, $key) use ($contentBeforeChange, $contentAfterChange, $removedContent, $addedContent) {
        if (array_key_exists($key, $removedContent)) {
            $acc[] = ['type' => 'removed',
                      'key' => $key,
                      'before' => $removedContent[$key]];
            return $acc;
        } elseif (array_key_exists($key, $addedContent)) {
            $acc[] = ['type' => 'added',
                      'key' => $key,
                      'after' => $addedContent[$key]];
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
