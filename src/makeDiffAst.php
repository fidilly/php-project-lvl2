<?php

namespace Differ\makeDiffAst;

use function Differ\getContents;

function makeDiffAst($contentBefore, $contentAfter)
{
    $missAfter = array_diff_key($contentBefore, $contentAfter);
    $missBefore = array_diff_key($contentAfter, $contentBefore);
    $withAllKeys = array_merge($contentBefore, $missBefore);

    $diffAst = array_reduce(array_keys($withAllKeys), function ($acc, $key) use ($withAllKeys, $contentAfter, $missAfter, $missBefore) {
        if (array_key_exists($key, $missAfter)) {
            return ['data' => 'removed', 'key' => $key, 'before' => $missAfter, 'after' => null];
        } elseif (array_key_exists($key, $missBefore)) {
            return ['data' => 'added', 'key' => $key, 'before' => null, 'after' => $missBefore];
        } elseif (array_key_exists($key, $contentAfter)) {
            $iterBefore = $withAllKeys[$key];
            $iterAfter = $contentAfter[$key];
            if (is_array($iterBefore) && is_array($iterAfter)) {
                return ['data' => 'unchanged', 'key' => $key, 'before' => $iterBefore, 'after' => makeDiffAst($iterBefore, $iterAfter)];
            } else {
                $data = ($iterBefore === $iterAfter) ? 'unchanged' : 'changed';
                return ['data' => $data, 'key' => $key, 'before' => $iterBefore, 'after' => $iterAfter];
            }
        }
    }, []);
    return $diffAst;
}
