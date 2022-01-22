<?php
declare(strict_types=1);

namespace Tests\Utilities\Directory;

use Ifrost\Common\Utilities\Directory\CountFilesInDirectoryAndSubDirectories;
use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\Directory\DeleteDirectoryWithAllContents;
use Ifrost\Common\Utilities\File\CreateFileIfNotExists;
use PHPUnit\Framework\TestCase;

class DeleteDirectoryWithAllContentsTest extends TestCase
{
    public function testShouldRemoveEmptyDirectory()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/empty-directory', DATA_DIRECTORY);
        (new CreateDirectoryIfNotExists($directoryPath))->execute();

        // When
        (new DeleteDirectoryWithAllContents($directoryPath))->execute();

        // Then
        $this->assertEquals(false, file_exists($directoryPath));
    }

    public function testShouldRemoveDirectoryWithOneFileInside()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/one-file-inside', DATA_DIRECTORY);
        $filename = sprintf('%s/something.txt', $directoryPath);
        (new CreateFileIfNotExists($filename))->execute();
        $files = glob($directoryPath . '/*', GLOB_MARK);
        $this->assertEquals(1, count($files));

        // When
        (new DeleteDirectoryWithAllContents($directoryPath))->execute();

        // Then
        $this->assertDirectoryDoesNotExist($directoryPath);
        $this->assertFileDoesNotExist($filename);
    }

    public function testShouldRemoveDirectoryWithTwoFilesInside()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/two-files-inside', DATA_DIRECTORY);
        $filename1 = sprintf('%s/something1.txt', $directoryPath);
        $filename2 = sprintf('%s/something2.txt', $directoryPath);
        (new CreateFileIfNotExists($filename1))->execute();
        (new CreateFileIfNotExists($filename2))->execute();
        $files = glob($directoryPath . '/*', GLOB_MARK);
        $this->assertEquals(2, count($files));

        // When
        (new DeleteDirectoryWithAllContents($directoryPath))->execute();

        // Then
        $this->assertDirectoryDoesNotExist($directoryPath);
        $this->assertFileDoesNotExist($filename1);
        $this->assertFileDoesNotExist($filename2);
    }

    public function testShouldRemoveDirectoryWithNestedDirectoriesWhichContainFiles()
    {
        // Expect & Given
        $mainDirectoryPath = sprintf('%s/dir-to-remove', DATA_DIRECTORY);
        $directoryPath2 = sprintf('%s/nested1/nested', $mainDirectoryPath);
        $directoryPath3 = sprintf('%s/nested2', $mainDirectoryPath);
        $filenames = [
            sprintf('%s/something1.txt', $mainDirectoryPath),
            sprintf('%s/something2.txt', $mainDirectoryPath),
            sprintf('%s/something1.txt', $directoryPath2),
            sprintf('%s/something2.txt', $directoryPath2),
            sprintf('%s/something1.txt', $directoryPath3),
            sprintf('%s/something2.txt', $directoryPath3),
        ];

        foreach ($filenames as $filename) {
            (new CreateFileIfNotExists($filename))->execute();
        }

        $this->assertEquals(6, (new CountFilesInDirectoryAndSubDirectories($mainDirectoryPath))->acquire());

        // When
        (new DeleteDirectoryWithAllContents($mainDirectoryPath))->execute();

        // Then
        $this->assertDirectoryDoesNotExist($mainDirectoryPath);
        foreach ($filenames as $filename) {
            $this->assertFileDoesNotExist($filename1);
        }
        $this->assertFileDoesNotExist($filename1);
        $this->assertFileDoesNotExist($filename2);
    }

    public function testShouldThrowInvalidArgumentExceptionWhenPathIsFile()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $directoryPath = sprintf('%s/some-dir', DATA_DIRECTORY);
        $filename = sprintf('%s/test.txt', $directoryPath);
        (new CreateFileIfNotExists($filename))->execute();
        $this->assertFileExists($filename);

        // When
        (new DeleteDirectoryWithAllContents($directoryPath))->execute();
    }
}
