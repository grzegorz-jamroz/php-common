<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\Directory;

use Ifrost\Common\Utilities\Directory\Directory;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class GetFilesAndSubDirectoriesFromDirectoryTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldReturnEmptyArrayWhenDirectoryIsEmpty()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/get-files-and-sub-dirs/empty_directory', DATA_DIRECTORY);
        $this->createDirectoryIfNotExists($directoryPath);
        $this->assertDirectoryExists($directoryPath);

        // When
        $files = (new Directory($directoryPath))->getFilesAndDirectories();

        // Then
        $this->assertEquals([], $files);
    }

    public function testShouldReturnArrayWithOneFilenameString()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/get-files-and-sub-dirs/one_file_inside', DATA_DIRECTORY);
        $filename = sprintf('%s/one.txt', $directoryPath);
        $this->createFileIfNotExists($filename);
        $this->createDirectoryIfNotExists($directoryPath);
        $this->assertDirectoryExists($directoryPath);
        $this->assertFileExists($filename);

        // When
        $files = (new Directory($directoryPath))->getFilesAndDirectories();

        // Then
        $this->assertEquals([$filename], $files);
    }

    public function testShouldReturnArrayWithTwoFilenamesOrderedByNameAsc()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/get-files-and-sub-dirs/two_files_inside', DATA_DIRECTORY);
        $filename1 = sprintf('%s/b_one.txt', $directoryPath);
        $filename2 = sprintf('%s/a_two.txt', $directoryPath);
        $this->createFileIfNotExists($filename1);
        $this->createFileIfNotExists($filename2);
        $this->createDirectoryIfNotExists($directoryPath);
        $this->assertDirectoryExists($directoryPath);
        $this->assertFileExists($filename1);
        $this->assertFileExists($filename2);

        // When
        $files = (new Directory($directoryPath))->getFilesAndDirectories();

        // Then
        $this->assertEquals([$filename2, $filename1], $files);
    }

    public function testShouldReturnArrayWithOneDirectoryPath()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/get-files-and-sub-dirs/one_dir_inside/one', DATA_DIRECTORY);
        $subDirectoryPath = sprintf('%s/one', $directoryPath);
        $this->createDirectoryIfNotExists($subDirectoryPath);
        $this->assertDirectoryExists($subDirectoryPath);

        // When
        $files = (new Directory($directoryPath))->getFilesAndDirectories();

        // Then
        $this->assertEquals([sprintf('%s/', $subDirectoryPath)], $files);
    }

    public function testShouldReturnArrayWithTwoDirectoryPathsOrderedByNameAsc()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/get-files-and-sub-dirs/two_directories_inside', DATA_DIRECTORY);
        $directoryPath1 = sprintf('%s/b_one', $directoryPath);
        $directoryPath2 = sprintf('%s/a_two', $directoryPath);
        $this->createDirectoryIfNotExists($directoryPath1);
        $this->createDirectoryIfNotExists($directoryPath2);
        $this->createDirectoryIfNotExists($directoryPath);
        $this->assertDirectoryExists($directoryPath);
        $this->assertDirectoryExists($directoryPath1);
        $this->assertDirectoryExists($directoryPath2);

        // When
        $files = (new Directory($directoryPath))->getFilesAndDirectories();

        // Then
        $this->assertEquals([
            sprintf('%s/', $directoryPath2),
            sprintf('%s/', $directoryPath1),
        ], $files);
    }

    public function testShouldReturnEightDirectoryPathsAndFiveFilesWhenRecursiveOptionIsEnabled()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/get-files-and-sub-dirs/recursive_test', DATA_DIRECTORY);
        (new Directory($directoryPath))->delete();
        $this->createDirectoryIfNotExists($directoryPath);
        $this->assertDirectoryExists($directoryPath);
        $paths = [
            sprintf('%s/b_one', $directoryPath),
            sprintf('%s/a/d_four', $directoryPath),
            sprintf('%s/b/a/c_three', $directoryPath),
            sprintf('%s/b/b/a_two', $directoryPath),
        ];
        $filenames = [
            sprintf('%s/file1.txt', $directoryPath),
            sprintf('%s/b_one/file2.txt', $directoryPath),
            sprintf('%s/a/file3.txt', $directoryPath),
            sprintf('%s/a/file4.txt', $directoryPath),
            sprintf('%s/b/b/file5.txt', $directoryPath),
        ];

        foreach ($paths as $path) {
            $this->createDirectoryIfNotExists($path);
            $this->assertDirectoryExists($path);
        }

        foreach ($filenames as $filename) {
            $this->createFileIfNotExists($filename);
            $this->assertFileExists($filename);
        }

        // When
        $files = (new Directory($directoryPath))->getFilesAndDirectories(['recursive' => true]);

        // Then
        $expect = [
            sprintf('%s/a/', $directoryPath),
            sprintf('%s/a/d_four/', $directoryPath),
            sprintf('%s/a/file3.txt', $directoryPath),
            sprintf('%s/a/file4.txt', $directoryPath),
            sprintf('%s/b/', $directoryPath),
            sprintf('%s/b/a/', $directoryPath),
            sprintf('%s/b/a/c_three/', $directoryPath),
            sprintf('%s/b/b/', $directoryPath),
            sprintf('%s/b/b/a_two/', $directoryPath),
            sprintf('%s/b/b/file5.txt', $directoryPath),
            sprintf('%s/b_one/', $directoryPath),
            sprintf('%s/b_one/file2.txt', $directoryPath),
            sprintf('%s/file1.txt', $directoryPath),
        ];
        $this->assertEquals($expect, $files);
    }

    public function testShouldReturnArrayWithResultsOrderedDesc()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/get-files-and-sub-dirs/json_test', DATA_DIRECTORY);
        $subDirectoryPath = sprintf('%s/directory/get-files-and-sub-dirs/json_test/super_dir', DATA_DIRECTORY);
        $filenames = [
            sprintf('%s/b_one.txt', $directoryPath),
            sprintf('%s/c_four.json', $directoryPath),
            sprintf('%s/c_three.txt', $directoryPath),
            sprintf('%s/a_two.json', $directoryPath),
        ];

        $this->createDirectoryIfNotExists($directoryPath);
        $this->createDirectoryIfNotExists($subDirectoryPath);
        $this->assertDirectoryExists($directoryPath);

        foreach ($filenames as $filename) {
            $this->createFileIfNotExists($filename);
            $this->assertFileExists($filename);
        }

        // When
        $files = (new Directory($directoryPath))->getFilesAndDirectories(['order' => 'DESC']);

        // Then
        $expect = [
            sprintf('%s/super_dir/', $directoryPath),
            sprintf('%s/c_three.txt', $directoryPath),
            sprintf('%s/c_four.json', $directoryPath),
            sprintf('%s/b_one.txt', $directoryPath),
            sprintf('%s/a_two.json', $directoryPath),
        ];
        $this->assertEquals($expect, $files);
    }

    public function testShouldThrowInvalidArgumentExceptionWhenDirectoryPathIsNotDirectory()
    {
        // Expect && Given
        $directoryPath = sprintf('%s/directory/get-files-and-sub-dirs', DATA_DIRECTORY);
        $filename = sprintf('%s/test.txt', $directoryPath);
        $this->createFileIfNotExists($filename);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s is not directory.', $filename));

        // When & Then
        (new Directory($filename))->getFilesAndDirectories();
    }

    public function testShouldThrowRuntimeExceptionWhenDirectoryDoesNotExist()
    {
        // Expect && Given
        $directoryPath = sprintf('%s/directory/get-files-and-sub-dirs/not_exist', DATA_DIRECTORY);
        (new Directory($directoryPath))->delete();
        $this->assertDirectoryDoesNotExist($directoryPath);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s is not directory.', $directoryPath));

        // When & Then
        (new Directory($directoryPath))->getFilesAndDirectories();
    }
}
