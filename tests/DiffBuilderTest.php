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

        return [
            'testJsonStylishDiff' => [
                $this->firstJsonFilePath,
                $this->secondJsonFilePath,
                'stylish',
                $this->expectedStylishDiff
            ],
            'testJsonPlainDiff' => [
                $this->firstJsonFilePath,
                $this->secondJsonFilePath,
                'plain',
                $this->expectedPlainDiff
            ],
            'testJsonJsonDiff' => [
                $this->firstJsonFilePath,
                $this->secondJsonFilePath,
                'json',
                $this->expectedJsonDiff
            ],
            'testYamlStylishDiff' => [
                $this->firstYamlFilePath,
                $this->secondYamlFilePath,
                'stylish',
                $this->expectedStylishDiff
            ],
            'testYamlPlainDiff' => [
                $this->firstYamlFilePath,
                $this->secondYamlFilePath,
                'plain',
                $this->expectedPlainDiff],
            'testYamlJsonDiff' => [
                $this->firstYamlFilePath,
                $this->secondYamlFilePath,
                'json',
                $this->expectedJsonDiff]
        ];
    }

    /**
     * @param string $firstPath
     * @param string $secondPath
     * @param string $format
     * @param string $expected
     * @dataProvider additionProvider
     * @throws \Exception
     */
    public function testGenDiff(string $firstPath, string $secondPath, string $format, string $expected): void
    {
        $this->assertEquals($expected, genDiff($firstPath, $secondPath, $format));
    }
}
