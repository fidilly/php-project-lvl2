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
            if(is_array($missAfter[$key])) {
                $acc[] = ['data' => 'removed', 'key' => $key, 'before' => makeDiffAst($missAfter[$key], $missAfter[$key]), 'after' => null];
                return $acc;
            }
            $acc[] = ['data' => 'removed', 'key' => $key, 'before' => $missAfter[$key], 'after' => null];
            return $acc;
        } elseif (array_key_exists($key, $missBefore)) {
            if(is_array($missBefore[$key])) {
                $acc[] = ['data' => 'added', 'key' => $key, 'before' => null, 'after' => makeDiffAst($missBefore[$key], $missBefore[$key])];
                return $acc;
            }
            $acc[] = ['data' => 'added', 'key' => $key, 'before' => null, 'after' => $missBefore[$key]];
            return $acc;
        } elseif (array_key_exists($key, $contentAfter)) {
            $iterBefore = $withAllKeys[$key];
            $iterAfter = $contentAfter[$key];
            if (is_array($iterBefore) && is_array($iterAfter)) {
                $acc[] = ['data' => 'unchanged', 'key' => $key, 'before' => null, 'after' => makeDiffAst($iterBefore, $iterAfter)];
                return $acc;
            } else {
                $data = ($iterBefore === $iterAfter) ? 'unchanged' : 'changed';
                $acc[] = ['data' => $data, 'key' => $key, 'before' => $iterBefore, 'after' => $iterAfter];
                return $acc;
            }
        }
    }, []);
    return $diffAst;
}
