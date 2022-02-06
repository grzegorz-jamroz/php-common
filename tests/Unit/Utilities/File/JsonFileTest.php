<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\Directory\Directory;
use Ifrost\Common\Utilities\File\JsonFile;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class JsonFileTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldCreateFileInNotExistedDirectoryWithGivenData()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/file/json-file/not_exist', DATA_DIRECTORY);
        $filename = sprintf('%s/test.json', $directoryPath);
        (new Directory($directoryPath))->delete();
        $this->assertDirectoryDoesNotExist($directoryPath);
        $this->assertFileDoesNotExist($filename);
        $data = [
            'product' => [
                'name' => 'kołki / szybki montaż',
                'price' => 1000,
                'size' => 0.6,
            ],
        ];

        // When
        (new JsonFile($filename))->create($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        // Then
        $this->assertFileExists($filename);
        $this->assertEquals($data, (new JsonFile($filename))->read());
    }

    public function testShouldOverwriteFileWithGivenData()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/file/json-file/overwrite', DATA_DIRECTORY);
        $filename = sprintf('%s/test.json', $directoryPath);
        (new Directory($directoryPath))->delete();
        $this->assertDirectoryDoesNotExist($directoryPath);
        $this->assertFileDoesNotExist($filename);
        $data = [
            'product' => [
                'name' => 'kołki / szybki montaż',
                'price' => 1000,
                'size' => 0.6,
            ],
        ];
        (new JsonFile($filename))->create($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        // When
        $newData = [
            'product' => [
                'name' => 'kołki / szybki montaż',
                'price' => 2000,
                'size' => 0.6,
            ],
        ];
        (new JsonFile($filename))->overwrite($newData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);


        // Then
        $this->assertFileExists($filename);
        $this->assertEquals($newData, (new JsonFile($filename))->read());
    }
}
