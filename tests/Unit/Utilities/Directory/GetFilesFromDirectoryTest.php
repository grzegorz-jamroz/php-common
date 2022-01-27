<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\Directory;

use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\Directory\DeleteDirectoryWithAllContent;
use Ifrost\Common\Utilities\Directory\GetFilesFromDirectory;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class GetFilesFromDirectoryTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        (new CreateDirectoryIfNotExists(DATA_DIRECTORY))->execute();
    }

    public function testShouldReturnEmptyArrayWhenDirectoryIsEmpty()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/get-files-from-directory/empty_directory', DATA_DIRECTORY);
        (new CreateDirectoryIfNotExists($directoryPath))->execute();
        $this->assertDirectoryExists($directoryPath);

        // When
        $files = (new GetFilesFromDirectory($directoryPath))->acquire();

        // Then
        $this->assertEquals([], $files);
    }

    public function testShouldReturnArrayWithOneFilenameString()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/get-files-from-directory/one_file_inside', DATA_DIRECTORY);
        $filename = sprintf('%s/one.txt', $directoryPath);
        $this->createFileIfNotExists($filename);
        (new CreateDirectoryIfNotExists($directoryPath))->execute();
        $this->assertDirectoryExists($directoryPath);
        $this->assertFileExists($filename);

        // When
        $files = (new GetFilesFromDirectory($directoryPath))->acquire();

        // Then
        $this->assertEquals([$filename], $files);
    }

    public function testShouldReturnArrayWithTwoFilenamesOrderedByNameAsc()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/get-files-from-directory/two_files_inside', DATA_DIRECTORY);
        $filename1 = sprintf('%s/b_one.txt', $directoryPath);
        $filename2 = sprintf('%s/a_two.txt', $directoryPath);
        $this->createFileIfNotExists($filename1);
        $this->createFileIfNotExists($filename2);
        (new CreateDirectoryIfNotExists($directoryPath))->execute();
        $this->assertDirectoryExists($directoryPath);
        $this->assertFileExists($filename1);
        $this->assertFileExists($filename2);

        // When
        $files = (new GetFilesFromDirectory($directoryPath))->acquire();

        // Then
        $this->assertEquals([$filename2, $filename1], $files);
    }

    public function testShouldSkipSubDirectoriesInTheOutput()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/get-files-from-directory/files_and_sub_dirs_inside', DATA_DIRECTORY);
        $filename1 = sprintf('%s/b_one.txt', $directoryPath);
        $filename2 = sprintf('%s/a_two.txt', $directoryPath);
        $subDirectory1 = sprintf('%s/sub_directory', $directoryPath);
        $subDirectory2 = sprintf('%s/a_one', $directoryPath);
        $this->createFileIfNotExists($filename1);
        $this->createFileIfNotExists($filename2);
        (new CreateDirectoryIfNotExists($directoryPath))->execute();
        (new CreateDirectoryIfNotExists($subDirectory1))->execute();
        (new CreateDirectoryIfNotExists($subDirectory2))->execute();
        $this->assertDirectoryExists($directoryPath);
        $this->assertDirectoryExists($subDirectory1);
        $this->assertDirectoryExists($subDirectory2);
        $this->assertFileExists($filename1);
        $this->assertFileExists($filename2);

        // When
        $files = (new GetFilesFromDirectory($directoryPath))->acquire();

        // Then
        $this->assertEquals([$filename2, $filename1], $files);
    }

    public function testShouldReturnArrayWithTwoJsonFilenamesOrderedByNameAsc()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/get-files-from-directory/json_test', DATA_DIRECTORY);
        $filenames = [
            sprintf('%s/b_one.txt', $directoryPath),
            sprintf('%s/c_four.json', $directoryPath),
            sprintf('%s/c_three.txt', $directoryPath),
            sprintf('%s/a_two.json', $directoryPath),
        ];

        (new CreateDirectoryIfNotExists($directoryPath))->execute();
        $this->assertDirectoryExists($directoryPath);

        foreach ($filenames as $filename) {
            $this->createFileIfNotExists($filename);
            $this->assertFileExists($filename);
        }

        // When
        $files = (new GetFilesFromDirectory(
            $directoryPath,
            ['extension' => 'json']
        ))->acquire();

        // Then
        $this->assertEquals([$filenames[3], $filenames[1]], $files);
    }

    public function testShouldThrowInvalidArgumentExceptionWhenDirectoryPathIsNotDirectory()
    {
        // Expect && Given
        $directoryPath = sprintf('%s/directory/get-files-from-directory', DATA_DIRECTORY);
        $filename = sprintf('%s/test.txt', $directoryPath);
        $this->createFileIfNotExists($filename);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s is not directory.', $filename));

        // When & Then
        (new GetFilesFromDirectory($filename))->acquire();
    }

    public function testShouldThrowRuntimeExceptionWhenDirectoryDoesNotExist()
    {
        // Expect && Given
        $directoryPath = sprintf('%s/directory/get-files-from-directory/not_exist', DATA_DIRECTORY);
        (new DeleteDirectoryWithAllContent($directoryPath))->execute();
        $this->assertDirectoryDoesNotExist($directoryPath);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('%s is not directory.', $directoryPath));

        // When & Then
        (new GetFilesFromDirectory($directoryPath))->acquire();
    }
}
