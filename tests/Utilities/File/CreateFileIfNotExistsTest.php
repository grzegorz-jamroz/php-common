<?php

declare(strict_types=1);

namespace Tests\Utilities\File;

use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\Directory\DeleteDirectoryWithAllContent;
use Ifrost\Common\Utilities\File\CreateFileIfNotExists;
use Ifrost\Common\Utilities\File\DeleteFile;
use PHPUnit\Framework\TestCase;

class CreateFileIfNotExistsTest extends TestCase
{
    public function testShouldLetNothingHappenWhenFileExists()
    {
        // Expect & Given
        $filename = sprintf('%s/exists.txt', TESTS_DATA_DIRECTORY);
        $this->assertFileExists($filename);
        $expectedFileContent = 'some text';
        file_put_contents($filename, $expectedFileContent);

        // When
        (new CreateFileIfNotExists($filename))->execute();

        // Then
        $this->assertEquals($expectedFileContent, file_get_contents($filename));
    }

    public function testShouldCreateFileInNotExistedDirectory()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/not_exists/folder', DATA_DIRECTORY);
        $filename = sprintf('%s/test.txt', $directoryPath);
        (new DeleteDirectoryWithAllContent($directoryPath))->execute();
        $this->assertDirectoryDoesNotExist($directoryPath);
        $this->assertFileDoesNotExist($filename);

        // When
        (new CreateFileIfNotExists($filename))->execute();

        // Then
        $this->assertFileExists($filename);
    }

    public function testShouldCreateFileInExistedDirectory()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/dir_exists', DATA_DIRECTORY);
        $filename = sprintf('%s/test.txt', $directoryPath);
        (new CreateDirectoryIfNotExists($directoryPath))->execute();
        (new DeleteFile($filename))->execute();
        $this->assertDirectoryExists($directoryPath);
        $this->assertFileDoesNotExist($filename);

        // When
        (new CreateFileIfNotExists($filename))->execute();

        // Then
        $this->assertFileExists($filename);
    }
}
