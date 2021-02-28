<?php

namespace Php\Project\Lvl2;

use PHPUnit\Framework\TestCase;

class JsonFileComparatorTest extends TestCase
{
    protected string $firstFilePath;
    protected string $secondFilePath;

    public function setUp(): void
    {
        $this->firstFilePath = __DIR__ . '/fixtures/file1.json';
        $this->secondFilePath = __DIR__ . '/fixtures/file2.json';
    }

    public function testGenDiff(): void
    {
        $expected1 = '{
- follow: false
  host: hexlet.io
- proxy: 123.234.53.22
- timeout: 50
+ timeout: 20
+ verbose: true
}';

        $expected2 = '{
+ follow: false
  host: hexlet.io
+ proxy: 123.234.53.22
- timeout: 20
+ timeout: 50
- verbose: true
}';

        $this->assertEquals($expected1, genDiff($this->firstFilePath, $this->secondFilePath));
        $this->assertEquals($expected2, genDiff($this->secondFilePath, $this->firstFilePath));
    }
}
