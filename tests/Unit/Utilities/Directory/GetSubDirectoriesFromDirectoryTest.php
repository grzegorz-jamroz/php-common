<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\Directory;

use Ifrost\Common\Utilities\Directory\Directory;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class GetSubDirectoriesFromDirectoryTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldReturnEmptyArrayWhenDirectoryIsEmpty()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/get-sub-directories-from-directory/empty_directory', DATA_DIRECTORY);
        $this->createDirectoryIfNotExists($directoryPath);
        $this->assertDirectoryExists($directoryPath);

        // When
        $files = (new Directory($directoryPath))->getDirectories();

        // Then
        $this->assertEquals([], $files);
    }

    public function testShouldReturnArrayWithOneDirectoryPath()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/get-sub-directories-from-directory/one_dir_inside/one', DATA_DIRECTORY);
        $subDirectoryPath = sprintf('%s/one', $directoryPath);
        $this->createDirectoryIfNotExists($subDirectoryPath);
        $this->assertDirectoryExists($subDirectoryPath);

        // When
        $files = (new Directory($directoryPath))->getDirectories();

        // Then
        $this->assertEquals([$subDirectoryPath], $files);
    }

    public function testShouldReturnArrayWithTwoDirectoryPathsOrderedByNameAsc()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/get-sub-directories-from-directory/two_files_inside', DATA_DIRECTORY);
        $directoryPath1 = sprintf('%s/b_one', $directoryPath);
        $directoryPath2 = sprintf('%s/a_two', $directoryPath);
        $this->createDirectoryIfNotExists($directoryPath1);
        $this->createDirectoryIfNotExists($directoryPath2);
        $this->createDirectoryIfNotExists($directoryPath);
        $this->assertDirectoryExists($directoryPath);
        $this->assertDirectoryExists($directoryPath1);
        $this->assertDirectoryExists($directoryPath2);

        // When
        $files = (new Directory($directoryPath))->getDirectories();

        // Then
        $this->assertEquals([$directoryPath2, $directoryPath1], $files);
    }

    public function testShouldSkipFilesInTheOutput()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/get-sub-directories-from-directory/files_and_sub_dirs_inside', DATA_DIRECTORY);
        $filename1 = sprintf('%s/b_one.txt', $directoryPath);
        $filename2 = sprintf('%s/a_two.txt', $directoryPath);
        $subDirectory1 = sprintf('%s/sub_directory', $directoryPath);
        $subDirectory2 = sprintf('%s/a_one', $directoryPath);
        $this->createFileIfNotExists($filename1);
        $this->createFileIfNotExists($filename2);
        $this->createDirectoryIfNotExists($directoryPath);
        $this->createDirectoryIfNotExists($subDirectory1);
        $this->createDirectoryIfNotExists($subDirectory2);
        $this->assertDirectoryExists($directoryPath);
        $this->assertDirectoryExists($subDirectory1);
        $this->assertDirectoryExists($subDirectory2);
        $this->assertFileExists($filename1);
        $this->assertFileExists($filename2);

        // When
        $files = (new Directory($directoryPath))->getDirectories();

        // Then
        $this->assertEquals([$subDirectory2, $subDirectory1], $files);
    }

    public function testShouldReturnFourFilenamesOrderedByNameDescWhenRecursiveOptionIsEnabled()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/get-sub-directories-from-directory/recursive_test', DATA_DIRECTORY);
        (new Directory($directoryPath))->delete();
        $this->createDirectoryIfNotExists($directoryPath);
        $this->assertDirectoryExists($directoryPath);
        $subDirectoryPaths = [
            sprintf('%s/b_one', $directoryPath),
            sprintf('%s/a/d_four', $directoryPath),
            sprintf('%s/b/a/c_three', $directoryPath),
            sprintf('%s/b/b/a_two', $directoryPath),
        ];

        foreach ($subDirectoryPaths as $subDirectoryPath) {
            $this->createDirectoryIfNotExists($subDirectoryPath);
            $this->assertDirectoryExists($subDirectoryPath);
        }

        // When
        $files = (new Directory($directoryPath))->getDirectories(['recursive' => true, 'order' => 'DESC']);

        // Then
        $expect = [
            sprintf('%s/b_one', $directoryPath),
            sprintf('%s/b/b/a_two', $directoryPath),
            sprintf('%s/b/b', $directoryPath),
            sprintf('%s/b/a/c_three', $directoryPath),
            sprintf('%s/b/a', $directoryPath),
            sprintf('%s/b', $directoryPath),
            sprintf('%s/a/d_four', $directoryPath),
            sprintf('%s/a', $directoryPath),
        ];
        $this->assertEquals($expect, $files);
    }

    public function testShouldReturnEightDirectoryPathsWhenRecursiveOptionIsEnabled()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/get-sub-directories-from-directory/recursive_test', DATA_DIRECTORY);
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
        $files = (new Directory($directoryPath))->getDirectories(['recursive' => true]);

        // Then
        $expect = [
            sprintf('%s/a', $directoryPath),
            sprintf('%s/a/d_four', $directoryPath),
            sprintf('%s/b', $directoryPath),
            sprintf('%s/b/a', $directoryPath),
            sprintf('%s/b/a/c_three', $directoryPath),
            sprintf('%s/b/b', $directoryPath),
            sprintf('%s/b/b/a_two', $directoryPath),
            sprintf('%s/b_one', $directoryPath),
        ];
        $this->assertEquals($expect, $files);
    }

    public function testShouldThrowInvalidArgumentExceptionWhenDirectoryPathIsNotDirectory()
    {
        // Expect && Given
        $directoryPath = sprintf('%s/directory/get-sub-directories-from-directory', DATA_DIRECTORY);
        $filename = sprintf('%s/test.txt', $directoryPath);
        $this->createFileIfNotExists($filename);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s is not directory.', $filename));

        // When & Then
        (new Directory($filename))->getDirectories();
    }

    public function testShouldThrowRuntimeExceptionWhenDirectoryDoesNotExist()
    {
        // Expect && Given
        $directoryPath = sprintf('%s/directory/get-sub-directories-from-directory/not_exist', DATA_DIRECTORY);
        (new Directory($directoryPath))->delete();
        $this->assertDirectoryDoesNotExist($directoryPath);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s is not directory.', $directoryPath));

        // When & Then
        (new Directory($directoryPath))->getDirectories();
    }
}
