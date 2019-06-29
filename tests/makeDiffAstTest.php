<?php

namespace Differ\Tests;

use function \Differ\makeDiffAst\makeDiffAst;
use function \Differ\getContents;
use PHPUnit\Framework\TestCase;

class MakeDiffAstTest extends TestCase
{
    public function testMakeDiffAst()
    {
        $array1 = ['key1' => 'value1', 'key2' => 'value2'];
        $array2 = ['key1' => 'value1', 'key2' => 'value2'];
        $actual1 = [['data' => 'unchanged', 'key' => 'key1', 'before' => 'value1', 'after' => 'value1'], 
                    ['data' => 'unchanged', 'key' => 'key2', 'before' => 'value2', 'after' => 'value2']];
        print_r($actual1);
        $expected1 = makeDiffAst($array1, $array2);
        print_r($expected1);
        $this->assertEquals($expected1, $actual1);

        $array3 = [['key1' => 'value1'], ['key2' => 'value2']];
        $array4 = [['key1' => 'value3'], ['key2' => 'value2']];
        $actual2 = [['data' => 'changed', 'key' => 'key1', 'before' => 'value1', 'after' => 'value3'], 
                    ['data' => 'unchanged', 'key' => 'key2', 'before' => 'value2', 'after' => 'value2']];
        $expected2 = makeDiffAst($array3, $array4);
        $this->assertEquals($expected2, $actual2);
    }
}
