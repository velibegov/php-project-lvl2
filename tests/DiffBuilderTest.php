<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DiffBuilderTest extends TestCase
{
    /**
     * @return array[]
     */
    public function additionProvider(): array
    {
        $fixturesPath = __DIR__ . '/fixtures/';

        $firstJsonFilePath = $fixturesPath . 'file1.json';
        $secondJsonFilePath = $fixturesPath . 'file2.json';
        $firstYamlFilePath = $fixturesPath . 'file1.yaml';
        $secondYamlFilePath = $fixturesPath . 'file2.yaml';
        $stylishDiffFilePath = $fixturesPath . 'stylishDiff';
        $plainDiffFilePath = $fixturesPath . 'plainDiff';
        $jsonDiffFilePath = $fixturesPath . 'jsonDiff';

        $expectedStylishDiff = file_get_contents($stylishDiffFilePath) ?: '';
        $expectedPlainDiff = file_get_contents($plainDiffFilePath) ?: '';
        $expectedJsonDiff = file_get_contents($jsonDiffFilePath) ?: '';

        return [
            'testJsonStylishDiff' => [
                $firstJsonFilePath,
                $secondJsonFilePath,
                'stylish',
                $expectedStylishDiff
            ],
            'testJsonPlainDiff' => [
                $firstJsonFilePath,
                $secondJsonFilePath,
                'plain',
                $expectedPlainDiff
            ],
            'testJsonJsonDiff' => [
                $firstJsonFilePath,
                $secondJsonFilePath,
                'json',
                $expectedJsonDiff
            ],
            'testYamlStylishDiff' => [
                $firstYamlFilePath,
                $secondYamlFilePath,
                'stylish',
                $expectedStylishDiff
            ],
            'testYamlPlainDiff' => [
                $firstYamlFilePath,
                $secondYamlFilePath,
                'plain',
                $expectedPlainDiff],
            'testYamlJsonDiff' => [
                $firstYamlFilePath,
                $secondYamlFilePath,
                'json',
                $expectedJsonDiff]
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
