<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\Directory;

use Ifrost\Common\Utilities\Directory\CountSubDirectoriesInDirectory;
use Ifrost\Common\Utilities\Directory\DeleteDirectoryWithAllContent;
use Ifrost\Common\Utilities\Directory\Directory;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class CountSubDirectoriesInDirectoryTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldReturnIntegerZeroWhenDirectoryDoesNotContainAnySubDirectories()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/count-directories/empty', DATA_DIRECTORY);
        (new DeleteDirectoryWithAllContent($directoryPath))->execute();
        $this->createDirectoryIfNotExists($directoryPath);
        $this->assertDirectoryExists($directoryPath);

        // When
        $actual = (new Directory($directoryPath))->countDirectories();

        // Then
        $this->assertEquals(0, $actual);
    }

    public function testShouldReturnIntegerOneWhenDirectoryContainsOneFile()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/count-directories/one-dir-inside', DATA_DIRECTORY);
        $subDirectoryPath = sprintf('%s/something', $directoryPath);
        (new DeleteDirectoryWithAllContent($directoryPath))->execute();
        $this->createDirectoryIfNotExists($subDirectoryPath);

        // When
        $actual = (new Directory($directoryPath))->countDirectories();

        // Then
        $this->assertEquals(1, $actual);
    }

    public function testShouldReturnIntegerOneWhenDirectoryContainsOneFileAndEmptySubdirectory()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/count-directories/one-file-inside-and-dir', DATA_DIRECTORY);
        $subDirectoryPath = sprintf('%s/test_dir', $directoryPath);
        $filename = sprintf('%s/something.txt', $directoryPath);
        $this->createFileIfNotExists($filename);
        $this->createDirectoryIfNotExists($subDirectoryPath);
        $this->assertFileExists($filename);
        $this->assertDirectoryExists($subDirectoryPath);

        // When
        $actual = (new Directory($directoryPath))->countDirectories();

        // Then
        $this->assertEquals(1, $actual);
    }

    public function testShouldReturnIntegerFourWhenWhenDirectoryContainsFourSubDirectories()
    {
        // Expect & Given
        $mainDirectoryPath = sprintf('%s/directory/count-directories/main', DATA_DIRECTORY);
        $directoryPath2 = sprintf('%s/nested1/nested', $mainDirectoryPath);
        $directoryPath3 = sprintf('%s/nested2', $mainDirectoryPath);
        $directoryPath4 = sprintf('%s/nested4', $mainDirectoryPath);
        $filenames = [
            sprintf('%s/something1.txt', $mainDirectoryPath),
            sprintf('%s/something2.txt', $mainDirectoryPath),
            sprintf('%s/something1.txt', $directoryPath2),
            sprintf('%s/something2.txt', $directoryPath2),
            sprintf('%s/something1.txt', $directoryPath3),
            sprintf('%s/something2.txt', $directoryPath3),
            sprintf('%s/something1.txt', $directoryPath4),
        ];

        foreach ($filenames as $filename) {
            $this->createFileIfNotExists($filename);
        }

        // When
        $actual = (new Directory($mainDirectoryPath))->countDirectories(['recursive' => true]);

        $this->assertEquals(4, $actual);
    }

    public function testShouldThrowInvalidArgumentExceptionWhenPathIsFile()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $directoryPath = sprintf('%s/directory/count-directories/some-dir', DATA_DIRECTORY);
        $filename = sprintf('%s/test.txt', $directoryPath);
        $this->createFileIfNotExists($filename);
        $this->assertFileExists($filename);

        // When
        (new Directory($filename))->countDirectories();
    }
}
