<?php

namespace Differ\Tests;

use function \Differ\genDiff;
use function \Differ\boolToString;
use PHPUnit\Framework\TestCase;

class GenDiffTest extends TestCase
{
    public function testGenDiffJson()
    {
        $expected1 = <<<EOD
{
    host: hexlet.io
  + timeout: 20
  - timeout: 50
  - proxy: 123.234.53.22
  + verbose: true
}
EOD;

        $actual1 = genDiff(__DIR__ . "/before.json", __DIR__ . "/after.json", "pretty");
        $this->assertEquals($expected1, $actual1);

        $expected2 = <<<EOD
{
    common: {
        setting1: Value 1
      - setting2: 200
        setting3: true
      - setting6: {
            key: value
        }
      + setting4: blah blah
      + setting5: {
            key5: value5
        }
    }
    group1: {
      + baz: bars
      - baz: bas
        foo: bar
    }
  - group2: {
        abc: 12345
    }
  + group3: {
        fee: 100500
    }
}
EOD;
        $actual2 = genDiff(__DIR__ . "/nestedBefore.json", __DIR__ . "/nestedAfter.json", "pretty");
        $this->assertEquals($expected2, $actual2);

        $expected3 = <<<EOD
Property 'common.setting2' was removed
Property 'common.setting6' was removed
Property 'common.setting4' was added with value: 'blah blah'
Property 'common.setting5' was added with value: 'complex value'
Property 'group1.baz' was changed. From 'bas' to 'bars'
Property 'group2' was removed
Property 'group3' was added with value: 'complex value'
EOD;

        $actual3 = genDiff(__DIR__ . "/nestedBefore.json", __DIR__ . "/nestedAfter.json", "plane");
        $this->assertEquals($expected3, $actual3);
    }
    
    public function testGenDiffYaml()
    {
        $expected = <<<EOD
{
    host: hexlet.io
  + timeout: 20
  - timeout: 50
  - proxy: 123.234.53.22
  + verbose: true
}
EOD;

        $actual = genDiff(__DIR__ . "/before.yaml", __DIR__ . "/after.yaml", 'pretty');
        $this->assertEquals($expected, $actual);
    }
}
