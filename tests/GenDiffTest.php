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

        $actual1 = genDiff(__DIR__ . "/before.json", __DIR__ . "/after.json");
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

        $actual2 = genDiff(__DIR__ . "/nestedBefore.json", __DIR__ . "/nestedAfter.json");
        $this->assertEquals($expected2, $actual2);
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

        $actual = genDiff(__DIR__ . "/before.yaml", __DIR__ . "/after.yaml");
        $this->assertEquals($expected, $actual);
    }


    public function testBoolToString()
    {
        $this->assertEquals('true', boolToString(true));
        $this->assertEquals('false', boolToString(false));
        $this->assertEquals('value1', boolToString('value1'));
    }
}
