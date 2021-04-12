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
    protected string $stylishDiffFilePath;
    protected string $plainDiffFilePath;
    protected string $jsonDiffFilePath;

    protected string $expectedStylishDiff;
    protected string $expectedPlainDiff;
    protected string $expectedJsonDiff;

    protected string $actualJsonStylishDiff;
    protected string $actualJsonPlainDiff;
    protected string $actualJsonJsonDiff;

    protected string $actualYamlStylishDiff;
    protected string $actualYamlPlainDiff;
    protected string $actualYamlJsonDiff;

    /**
     * @return array[]
     * @throws \Exception
     */
    public function additionProvider(): array
    {
        $this->firstJsonFilePath = __DIR__ . '/fixtures/file1.json';
        $this->secondJsonFilePath = __DIR__ . '/fixtures/file2.json';
        $this->firstYamlFilePath = __DIR__ . '/fixtures/file1.yaml';
        $this->secondYamlFilePath = __DIR__ . '/fixtures/file2.yaml';
        $this->stylishDiffFilePath = __DIR__ . '/fixtures/stylishDiff';
        $this->plainDiffFilePath = __DIR__ . '/fixtures/plainDiff';
        $this->jsonDiffFilePath = __DIR__ . '/fixtures/jsonDiff';
        $this->expectedStylishDiff = file_get_contents($this->stylishDiffFilePath) ?: '';
        $this->expectedPlainDiff = file_get_contents($this->plainDiffFilePath) ?: '';
        $this->expectedJsonDiff = file_get_contents($this->jsonDiffFilePath) ?: '';

        $this->actualJsonStylishDiff = genDiff($this->firstJsonFilePath, $this->secondJsonFilePath);
        $this->actualJsonPlainDiff = genDiff($this->firstJsonFilePath, $this->secondJsonFilePath, 'plain');
        $this->actualJsonJsonDiff = genDiff($this->firstJsonFilePath, $this->secondJsonFilePath, 'json');

        $this->actualYamlStylishDiff = genDiff($this->firstYamlFilePath, $this->secondYamlFilePath);
        $this->actualYamlPlainDiff = genDiff($this->firstYamlFilePath, $this->secondYamlFilePath, 'plain');
        $this->actualYamlJsonDiff = genDiff($this->firstYamlFilePath, $this->secondYamlFilePath, 'json');

        return [
            'testJsonStylishDiff' => [$this->actualJsonStylishDiff, $this->expectedStylishDiff],
            'testJsonPlainDiff' => [$this->actualJsonPlainDiff, $this->expectedPlainDiff],
            'testJsonJsonDiff' => [$this->actualJsonJsonDiff, $this->expectedJsonDiff],
            'testYamlStylishDiff' => [$this->actualYamlStylishDiff, $this->expectedStylishDiff],
            'testYamlPlainDiff' => [$this->actualYamlPlainDiff, $this->expectedPlainDiff],
            'testYamlJsonDiff' => [$this->actualYamlJsonDiff, $this->expectedJsonDiff]
        ];
    }

    /**
     * @param string $expected
     * @param string $actual
     * @dataProvider additionProvider
     */
    public function testGenDiff(string $expected, string $actual): void
    {
        $this->assertEquals($expected, $actual);
    }
}
