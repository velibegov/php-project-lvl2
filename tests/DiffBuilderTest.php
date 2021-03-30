<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DiffBuilderTest extends TestCase
{
    protected string $firstJsonFilePath;
    protected string $secondJsonFilePath;
    protected string $firstYamlFilePath;
    protected string $secondYamlFilePath;

    protected string $expectedStylishDiff;
    protected string $expectedPlainDiff;
    protected string $expectedJsonDiff;

    public function setUp(): void
    {
        $this->firstJsonFilePath = __DIR__ . '/fixtures/file1.json';
        $this->secondJsonFilePath = __DIR__ . '/fixtures/file2.json';
        $this->firstYamlFilePath = __DIR__ . '/fixtures/file1.yaml';
        $this->secondYamlFilePath = __DIR__ . '/fixtures/file2.yaml';
    }

    public function testJsonStylishDiff(): void
    {
        $this->expectedStylishDiff = file_get_contents(__DIR__ . '/fixtures/stylishDiff') ?: '';
        $this->assertEquals(
            $this->expectedStylishDiff,
            genDiff($this->firstJsonFilePath, $this->secondJsonFilePath)
        );
    }

    public function testJsonPlainDiff(): void
    {
        $this->expectedPlainDiff = file_get_contents(__DIR__ . '/fixtures/plainDiff') ?: '';
        $this->assertEquals(
            $this->expectedPlainDiff,
            genDiff($this->firstJsonFilePath, $this->secondJsonFilePath, 'plain')
        );
    }

    public function testJsonJsonDiff(): void
    {
        $this->expectedJsonDiff = file_get_contents(__DIR__ . '/fixtures/jsonDiff') ?: '';
        $this->assertEquals(
            $this->expectedJsonDiff,
            genDiff($this->firstJsonFilePath, $this->secondJsonFilePath, 'json')
        );
    }

    public function testYamlStylishDiff(): void
    {
        $this->expectedStylishDiff = file_get_contents(__DIR__ . '/fixtures/stylishDiff') ?: '';
        $this->assertEquals(
            $this->expectedStylishDiff,
            genDiff($this->firstYamlFilePath, $this->secondYamlFilePath)
        );
    }

    public function testYamlPlainDiff(): void
    {
        $this->expectedPlainDiff = file_get_contents(__DIR__ . '/fixtures/plainDiff') ?: '';
        $this->assertEquals(
            $this->expectedPlainDiff,
            genDiff($this->firstYamlFilePath, $this->secondYamlFilePath, 'plain')
        );
    }

    public function testYamlJsonDiff(): void
    {
        $this->expectedJsonDiff = file_get_contents(__DIR__ . '/fixtures/jsonDiff') ?: '';
        $this->assertEquals(
            $this->expectedJsonDiff,
            genDiff($this->firstYamlFilePath, $this->secondYamlFilePath, 'json')
        );
    }
}
