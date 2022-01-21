<?php

declare(strict_types=1);

namespace Tests\Utilities\File;

use Ifrost\Common\Utilities\Directory\DeleteDirectoryWithAllContents;
use Ifrost\Common\Utilities\File\CreateFileIfNotExists;
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
        (new DeleteDirectoryWithAllContents($directoryPath))->execute();
        $this->assertDirectoryDoesNotExist($directoryPath);
        $this->assertFileDoesNotExist($filename);

        // When
        (new CreateFileIfNotExists($filename))->execute();

        // Then
        $this->assertFileExists($filename);
    }
}
