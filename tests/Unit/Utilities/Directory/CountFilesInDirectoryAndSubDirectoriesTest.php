<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\Directory;

use Ifrost\Common\Utilities\Directory\CountFilesInDirectoryAndSubDirectories;
use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\Directory\DeleteDirectoryWithAllContent;
use Ifrost\Common\Utilities\File\CreateFileIfNotExists;
use PHPUnit\Framework\TestCase;

class CountFilesInDirectoryAndSubDirectoriesTest extends TestCase
{
    protected function setUp(): void
    {
        (new CreateDirectoryIfNotExists(DATA_DIRECTORY))->execute();
    }

    public function testShouldReturnIntegerZeroWhenDirectoryDoesNotContainAnyFiles()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/empty', DATA_DIRECTORY);
        (new DeleteDirectoryWithAllContent($directoryPath))->execute();
        (new CreateDirectoryIfNotExists($directoryPath))->execute();
        $this->assertDirectoryExists($directoryPath);
        $files = glob($directoryPath . '/*', GLOB_MARK);
        $this->assertEquals(0, count($files));

        // When
        $actual = (new CountFilesInDirectoryAndSubDirectories($directoryPath))->acquire();

        // Then
        $this->assertEquals(0, $actual);
    }

    public function testShouldReturnIntegerOneWhenDirectoryContainsOneFile()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/one-file-inside', DATA_DIRECTORY);
        $filename = sprintf('%s/something.txt', $directoryPath);
        (new DeleteDirectoryWithAllContent($directoryPath))->execute();
        (new CreateFileIfNotExists($filename))->execute();
        $files = glob($directoryPath . '/*', GLOB_MARK);
        $this->assertEquals(1, count($files));

        // When
        $actual = (new CountFilesInDirectoryAndSubDirectories($directoryPath))->acquire();

        // Then
        $this->assertEquals(1, $actual);
    }

    public function testShouldReturnIntegerOneWhenDirectoryContainsOneFileAndEmptySubdirectory()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/one-file-inside-and-empty-dir', DATA_DIRECTORY);
        $subDirectoryPath = sprintf('%s/empty', $directoryPath);
        $filename = sprintf('%s/something.txt', $directoryPath);
        (new CreateFileIfNotExists($filename))->execute();
        (new CreateDirectoryIfNotExists($subDirectoryPath))->execute();
        $this->assertFileExists($filename);
        $this->assertDirectoryExists($subDirectoryPath);

        // When
        $actual = (new CountFilesInDirectoryAndSubDirectories($directoryPath))->acquire();

        // Then
        $this->assertEquals(1, $actual);
    }

    public function testShouldReturnIntegerSixWhenWhenDirectoryContainsSixFilesAndSomeOfThemAreInSubDirectories()
    {
        // Expect & Given
        $mainDirectoryPath = sprintf('%s/main', DATA_DIRECTORY);
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

        // When
        $actual = (new CountFilesInDirectoryAndSubDirectories($mainDirectoryPath))->acquire();

        $this->assertEquals(6, $actual);
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
        (new CountFilesInDirectoryAndSubDirectories($filename))->acquire();
    }
}
