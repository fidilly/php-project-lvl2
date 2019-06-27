<?php

namespace Differ\Tests;

use function \Differ\genDiff;
use PHPUnit\Framework\TestCase;

class GenDiffTest extends TestCase
{
    public function testGenDiff()
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

        $actual = genDiff(__DIR__ . "/before.json", __DIR__ . "/after.json");
#        echo __DIR__ . "/before.json",
        $this->assertEquals($expected, $actual);
    }
}
