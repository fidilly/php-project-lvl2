<?php

namespace Differ\Tests\GenDiffTest;

use function \Differ\Gendiff\gendiff;
use PHPUnit\Framework\TestCase;

class GenDiffTest extends TestCase
{
    public function testGenDiffJson()
    {
        $expected1 = file_get_contents(__DIR__ . "/Fixtures/expectedFlatPlain");
        $actual1 = gendiff(__DIR__ . "/Fixtures/before.json", __DIR__ . "/Fixtures/after.json", "pretty");
        $this->assertEquals($expected1, $actual1);

        $expected2 = file_get_contents(__DIR__ . "/Fixtures/expectedNestedPretty");
        $actual2 = gendiff(__DIR__ . "/Fixtures/nestedBefore.json", __DIR__ . "/Fixtures/nestedAfter.json", "pretty");
        $this->assertEquals($expected2, $actual2);

        #$expected3 = file_get_contents(__DIR__ . "/Fixtures/expectedNestedPlain");
        #$actual3 = gendiff(__DIR__ . "/Fixtures/nestedBefore.json", __DIR__ . "/Fixtures/nestedAfter.json", "plain");
        #$this->assertEquals($expected3, $actual3);
    }
    
    public function testGenDiffYaml()
    {
        $expected = file_get_contents(__DIR__ . "/Fixtures/expectedFlatPlain");
        $actual = gendiff(__DIR__ . "/Fixtures/before.yaml", __DIR__ . "/Fixtures/after.yaml", 'pretty');
        $this->assertEquals($expected, $actual);
    }
}
