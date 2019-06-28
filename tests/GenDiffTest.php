<?php

namespace Differ\Tests;

use function \Differ\genDiff;
use function \Differ\boolToString;
use PHPUnit\Framework\TestCase;

class GenDiffTest extends TestCase
{
    public function testGenDiffJson()
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

        $actual1 = genDiff(__DIR__ . "/before.json", __DIR__ . "/after.json");
        $this->assertEquals($expected, $actual1);
        
        $expected = <<<EOD
{
    host: hexlet.io
  + timeout: 20
  - timeout: 50
  - proxy: 123.234.53.22
  + verbose: true
}

EOD;

        $actual2 = genDiff(__DIR__ . "/nestedBefore.json", __DIR__ . "/nestedAfter.json");
        $this->assertEquals($expected, $actual2);
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
