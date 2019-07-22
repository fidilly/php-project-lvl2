<?php

namespace Differ\MakeDiffAst;

use function Differ\getContents;

function makeDiffAst($ContentBeforeChange, $ContentAfterChange)
{
    $removedContent = array_diff_key($ContentBeforeChange, $ContentAfterChange);
    $addedContent = array_diff_key($ContentAfterChange, $ContentBeforeChange);
    $overallContent = array_merge($ContentBeforeChange, $addedContent);

    $makeDiff = function ($acc, $key) use ($overallContent, $ContentAfterChange, $removedContent, $addedContent) {
        if (array_key_exists($key, $removedContent)) {
            $acc[] = ['type' => 'removed',
                      'key' => $key,
                      'before' => $removedContent[$key],
                      'after' => null];
            return $acc;
        } elseif (array_key_exists($key, $addedContent)) {
            $acc[] = ['type' => 'added',
                      'key' => $key,
                      'before' => null,
                      'after' => $addedContent[$key]];
            return $acc;
        } elseif (array_key_exists($key, $ContentAfterChange)) {
            $before = $overallContent[$key];
            $after = $ContentAfterChange[$key];
            if (is_array($before) && is_array($after)) {
                $acc[] = ['type' => 'nested',
                          'key' => $key,
                          'before' => null,
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

    $diffAst = array_reduce(array_keys($overallContent), $makeDiff, []);
    return $diffAst;
}
