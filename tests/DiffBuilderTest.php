<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function Php\Project\Lvl2\genDiff;

class DiffBuilderTest extends TestCase
{
    protected string $firstFilePath;
    protected string $secondFilePath;
    protected string $expectedStylishDiff;
    protected string $expectedPlainDiff;

    public function setUp(): void
    {
        $this->firstFilePath = __DIR__ . '/fixtures/file1.json';
        $this->secondFilePath = __DIR__ . '/fixtures/file2.json';
        $this->expectedStylishDiff = file_get_contents(__DIR__ . '/fixtures/stylishDiff') ? : '';
        $this->expectedPlainDiff = file_get_contents(__DIR__ . '/fixtures/plainDiff') ? : '';
    }

    public function testGenDiff(): void
    {
        $this->assertEquals($this->expectedStylishDiff, genDiff($this->firstFilePath, $this->secondFilePath));
        $this->assertEquals($this->expectedPlainDiff, genDiff($this->firstFilePath, $this->secondFilePath, 'plain'));
    }
}
