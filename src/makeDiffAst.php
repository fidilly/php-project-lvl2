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
            $acc[] = ['data' => 'removed', 'key' => $key, 'before' => $missAfter[$key], 'after' => null];
            return $acc;
        } elseif (array_key_exists($key, $missBefore)) {
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
