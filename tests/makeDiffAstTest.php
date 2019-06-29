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
        $expected1 = [['data' => 'unchanged', 'key' => 'key1', 'before' => 'value1', 'after' => 'value1'], 
                      ['data' => 'unchanged', 'key' => 'key2', 'before' => 'value2', 'after' => 'value2']];
        $actual1 = makeDiffAst($array1, $array2);
        $this->assertEquals($expected1, $actual1);

        $array3 = ['key1' => 'value1', 'key2' => 'value2'];
        $array4 = ['key1' => 'value3', 'key2' => 'value2'];
        $expected2 = [['data' => 'changed', 'key' => 'key1', 'before' => 'value1', 'after' => 'value3'], 
                      ['data' => 'unchanged', 'key' => 'key2', 'before' => 'value2', 'after' => 'value2']];
        $actual2 = makeDiffAst($array3, $array4);
        $this->assertEquals($expected2, $actual2);

        $array5 = ['key1' => 'value1', 'key2' => 'value2'];
        $array6 = ['key2' => 'value2'];
        $expected3 = [['data' => 'removed', 'key' => 'key1', 'before' => 'value1', 'after' => null], 
                      ['data' => 'unchanged', 'key' => 'key2', 'before' => 'value2', 'after' => 'value2']];
        $actual3 = makeDiffAst($array5, $array6);
        $this->assertEquals($expected3, $actual3);

        $array7 = ['key1' => 'value1'];
        $array8 = ['key1' => 'value1', 'key2' => 'value2'];
        $expected4 = [['data' => 'unchanged', 'key' => 'key1', 'before' => 'value1', 'after' => 'value1'], 
                      ['data' => 'added', 'key' => 'key2', 'before' => null, 'after' => 'value2']];
        $actual4 = makeDiffAst($array7, $array8);
        $this->assertEquals($expected4, $actual4);
 
        $content1 = getContents(__DIR__ . "/before.json", __DIR__ . "/after.json");
        [$array9, $array10] = $content1;
        
        $expected5 = [['data' => 'unchanged', 'key' => 'host', 'before' => 'hexlet.io', 'after' => 'hexlet.io'],
                      ['data' => 'changed', 'key' => 'timeout', 'before' => 50, 'after' => 20],
                      ['data' => 'removed', 'key' => 'proxy', 'before' => '123.234.53.22', 'after' => null],
                      ['data' => 'added', 'key' => 'verbose', 'before' => null, 'after' => true]];
        $actual5 = makeDiffAst($array9, $array10);
        $this->assertEquals($expected5, $actual5);

        $content2 = getContents(__DIR__ . "/before.yaml", __DIR__ . "/after.yaml");
        [$array11, $array12] = $content2;

        $expected6 = [['data' => 'unchanged', 'key' => 'host', 'before' => 'hexlet.io', 'after' => 'hexlet.io'],
                      ['data' => 'changed', 'key' => 'timeout', 'before' => 50, 'after' => 20],
                      ['data' => 'removed', 'key' => 'proxy', 'before' => '123.234.53.22', 'after' => null],
                      ['data' => 'added', 'key' => 'verbose', 'before' => null, 'after' => true]];
        $actual6 = makeDiffAst($array11, $array12);
        $this->assertEquals($expected6, $actual6);

        $array13 = ['key1' => ['key1' => 'value1', 'key2' => 'value2'], 'key2' => ['key1' => 'value1', 'key2' => 'value2'], 'key3' => ['key1' => 'value1', 'key3' => 'value3', 'key4' => 'value']];
        $array14 = ['key1' => ['key1' => 'value3', 'key2' => 'value2'], 'key2' => ['key1' => 'value1', 'key2' => 'value2'], 'key3' => ['key1' => 'value3', 'key2' => 'value2', 'key4' => 'value']];
        $expected7 = [['data' => 'unchanged', 'key' => 'key1', 'before' => null, 
                                                               'after' => [['data' => 'changed', 'key' => 'key1', 'before' => 'value1', 'after' => 'value3'],
                                                                            ['data' => 'unchanged', 'key' => 'key2', 'before' => 'value2', 'after' => 'value2']]], 
                      ['data' => 'unchanged', 'key' => 'key2', 'before' => null,
                                                               'after' => [['data' => 'unchanged', 'key' => 'key1', 'before' => 'value1', 'after' => 'value1'], 
                                                                          ['data' => 'unchanged', 'key' => 'key2', 'before' => 'value2', 'after' => 'value2']]],
                      ['data' => 'unchanged', 'key' => 'key3', 'before' => null,
                                                               'after' => [['data' => 'changed', 'key' => 'key1', 'before' => 'value1', 'after' => 'value3'],
                                                                           ['data' => 'removed', 'key' => 'key3', 'before' => 'value3', 'after' => null],
                                                                           ['data' => 'unchanged', 'key' => 'key4', 'before' => 'value', 'after' => 'value'],
                                                                           ['data' => 'added', 'key' => 'key2', 'before' => null, 'after' => 'value2']]]];
        $actual7 = makeDiffAst($array13, $array14);
        $this->assertEquals($expected1, $actual1);
    }
}
