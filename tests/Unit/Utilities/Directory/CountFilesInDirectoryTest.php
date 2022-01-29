<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\Directory;

use Ifrost\Common\Utilities\Directory\CountFilesInDirectory;
use Ifrost\Common\Utilities\Directory\DeleteDirectoryWithAllContent;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class CountFilesInDirectoryTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldReturnIntegerZeroWhenDirectoryDoesNotContainAnyFiles()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/count-files/empty', DATA_DIRECTORY);
        (new DeleteDirectoryWithAllContent($directoryPath))->execute();
        $this->createDirectoryIfNotExists($directoryPath);
        $this->assertDirectoryExists($directoryPath);
        $files = glob($directoryPath . '/*', GLOB_MARK);
        $this->assertEquals(0, count($files));

        // When
        $actual = (new CountFilesInDirectory($directoryPath))->acquire();

        // Then
        $this->assertEquals(0, $actual);
    }

    public function testShouldReturnIntegerOneWhenDirectoryContainsOneFile()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/count-files/one-file-inside', DATA_DIRECTORY);
        $filename = sprintf('%s/something.txt', $directoryPath);
        (new DeleteDirectoryWithAllContent($directoryPath))->execute();
        $this->createFileIfNotExists($filename);
        $files = glob($directoryPath . '/*', GLOB_MARK);
        $this->assertEquals(1, count($files));

        // When
        $actual = (new CountFilesInDirectory($directoryPath))->acquire();

        // Then
        $this->assertEquals(1, $actual);
    }

    public function testShouldReturnIntegerOneWhenDirectoryContainsOneFileAndEmptySubdirectory()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/count-files/one-file-inside-and-empty-dir', DATA_DIRECTORY);
        $subDirectoryPath = sprintf('%s/empty', $directoryPath);
        $filename = sprintf('%s/something.txt', $directoryPath);
        $this->createFileIfNotExists($filename);
        $this->createDirectoryIfNotExists($subDirectoryPath);
        $this->assertFileExists($filename);
        $this->assertDirectoryExists($subDirectoryPath);

        // When
        $actual = (new CountFilesInDirectory($directoryPath))->acquire();

        // Then
        $this->assertEquals(1, $actual);
    }

    public function testShouldReturnIntegerSixWhenWhenDirectoryContainsSixFilesAndSomeOfThemAreInSubDirectories()
    {
        // Expect & Given
        $mainDirectoryPath = sprintf('%s/directory/count-files/main', DATA_DIRECTORY);
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
            $this->createFileIfNotExists($filename);
        }

        // When
        $actual = (new CountFilesInDirectory($mainDirectoryPath, ['recursive' => true]))->acquire();

        $this->assertEquals(6, $actual);
    }

    public function testShouldThrowInvalidArgumentExceptionWhenPathIsFile()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $directoryPath = sprintf('%s/directory/count-files/some-dir', DATA_DIRECTORY);
        $filename = sprintf('%s/test.txt', $directoryPath);
        $this->createFileIfNotExists($filename);
        $this->assertFileExists($filename);

        // When
        (new CountFilesInDirectory($filename))->acquire();
    }
}
