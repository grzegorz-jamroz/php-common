<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\Directory;

use Ifrost\Common\Utilities\Directory\Directory;
use Ifrost\Common\Utilities\Directory\Exception\DirectoryAlreadyExists;
use Ifrost\Common\Utilities\Directory\Exception\DirectoryNotExist;
use Ifrost\Common\Utilities\File\TextFile;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class CopyDirectoryTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldCopyEmptyDirectoryInsideSameDirectory()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/copy-directory', DATA_DIRECTORY);
        $oldDirectoryPath = sprintf('%s/somedir', $directoryPath);
        $newDirectoryPath = sprintf('%s/somedir-copy', $directoryPath);
        (new Directory($oldDirectoryPath))->delete();
        $this->createDirectoryIfNotExists($oldDirectoryPath);
        $this->assertDirectoryExists($oldDirectoryPath);
        (new Directory($newDirectoryPath))->delete();
        $this->assertDirectoryDoesNotExist($newDirectoryPath);

        // When
        (new Directory($oldDirectoryPath))->copy($newDirectoryPath);

        // Then
        $this->assertDirectoryExists($oldDirectoryPath);
        $this->assertDirectoryExists($newDirectoryPath);
    }

    public function testShouldCopyDirectoryContentInsideSameDirectory()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/copy-directory', DATA_DIRECTORY);
        $oldDirectoryPath = sprintf('%s/somedir', $directoryPath);
        $filename1 = sprintf('%s/file_one.txt', $oldDirectoryPath);
        $filename2 = sprintf('%s/subdir/file_two.txt', $oldDirectoryPath);
        $newDirectoryPath = sprintf('%s/somedir-copy', $directoryPath);
        (new Directory($oldDirectoryPath))->delete();
        $this->createDirectoryIfNotExists($oldDirectoryPath);
        $this->createFileIfNotExists($filename1, 'text 1');
        $this->createFileIfNotExists($filename2, 'text 2');
        $this->assertDirectoryExists($oldDirectoryPath);
        $this->assertFileExists($filename1);
        $this->assertFileExists($filename2);
        (new Directory($newDirectoryPath))->delete();
        $this->assertDirectoryDoesNotExist($newDirectoryPath);

        // When
        (new Directory($oldDirectoryPath))->copy($newDirectoryPath);

        // Then
        $this->assertFileExists($oldDirectoryPath);
        $this->assertFileExists($newDirectoryPath);
        $this->assertEquals(3, (new Directory($newDirectoryPath))->countFilesAndDirectories(['recursive' => true]));
        $this->assertEquals('text 1', (new TextFile(sprintf('%s/file_one.txt', $newDirectoryPath)))->read());
        $this->assertEquals('text 2', (new TextFile(sprintf('%s/subdir/file_two.txt', $newDirectoryPath)))->read());
    }

    public function testShouldCopyDirectoryAndMoveToNewNotExistedDirectory()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/copy-directory', DATA_DIRECTORY);
        $oldDirectoryPath = sprintf('%s/dogs', $directoryPath);
        $newDirectoryPath = sprintf('%s/new_directory/dogs', $directoryPath);
        $filename1 = sprintf('%s/file_one.txt', $oldDirectoryPath);
        $filename2 = sprintf('%s/subdir/file_two.txt', $oldDirectoryPath);
        (new Directory($oldDirectoryPath))->delete();
        $this->createDirectoryIfNotExists($oldDirectoryPath);
        $this->createFileIfNotExists($filename1, 'dog 1');
        $this->createFileIfNotExists($filename2, 'dog 2');
        $this->assertDirectoryExists($oldDirectoryPath);
        $this->assertFileExists($filename1);
        $this->assertFileExists($filename2);
        (new Directory($newDirectoryPath))->delete();
        $this->assertDirectoryDoesNotExist($newDirectoryPath);

        // When
        (new Directory($oldDirectoryPath))->copy($newDirectoryPath);

        // Then
        $this->assertFileExists($oldDirectoryPath);
        $this->assertFileExists($newDirectoryPath);
        $this->assertEquals(3, (new Directory($newDirectoryPath))->countFilesAndDirectories(['recursive' => true]));
        $this->assertEquals('dog 1', (new TextFile(sprintf('%s/file_one.txt', $newDirectoryPath)))->read());
        $this->assertEquals('dog 2', (new TextFile(sprintf('%s/subdir/file_two.txt', $newDirectoryPath)))->read());
    }

    public function testShouldThrowDirectoryNotExistWhenOldDirectoryDoesNotExist()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/copy-directory', DATA_DIRECTORY);
        $oldDirectoryPath = sprintf('%s/exists_old', $directoryPath);
        $newDirectoryPath = sprintf('%s/exists_new', $directoryPath);
        $this->expectException(DirectoryNotExist::class);
        $this->expectExceptionMessage(sprintf('Unable copy directory "%s". Old directory does not exist.', $oldDirectoryPath));
        (new Directory($oldDirectoryPath))->delete();
        (new Directory($newDirectoryPath))->delete();
        $this->assertDirectoryDoesNotExist($oldDirectoryPath);
        $this->assertDirectoryDoesNotExist($newDirectoryPath);

        // When & Then
        (new Directory($oldDirectoryPath))->copy($newDirectoryPath);
    }

    public function testShouldThrowDirectoryAlreadyExistsWhenNewDirectoryExists()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/copy-directory', DATA_DIRECTORY);
        $oldDirectoryPath = sprintf('%s/exists_old_2', $directoryPath);
        $newDirectoryPath = sprintf('%s/exists_new_2', $directoryPath);
        $this->expectException(DirectoryAlreadyExists::class);
        $this->expectExceptionMessage(sprintf('Unable copy directory "%s". New directory already exists.', $newDirectoryPath));
        $this->createDirectoryIfNotExists($oldDirectoryPath);
        $this->createDirectoryIfNotExists($newDirectoryPath);
        $this->assertDirectoryExists($oldDirectoryPath);
        $this->assertDirectoryExists($newDirectoryPath);

        // When & Then
        (new Directory($oldDirectoryPath))->copy($newDirectoryPath);
    }

    /**
     * it probably only works on ext2/ext3/ext4 filesystems.
     */
    public function testShouldThrowRuntimeExceptionWhenUnableToCopyDirectory()
    {
        $this->endTestIfWindowsOs($this);
        $this->endTestIfEnvMissing($this, ['SUDOER_PASSWORD']);

        // Expect & Given
        $oldDirectoryPath = sprintf('%s/immutable_dir_with_content', TESTS_DATA_DIRECTORY);
        $filename = sprintf('%s/protected.txt', $oldDirectoryPath);
        $newDirectoryPath = sprintf('%s/immutable_dir_new', TESTS_DATA_DIRECTORY);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable copy directory "%s".', $oldDirectoryPath));
        (new Directory($newDirectoryPath))->delete();
        $this->createDirectoryIfNotExists($oldDirectoryPath);

        try {
            $this->createFileIfNotExists($oldDirectoryPath);
        } catch (\Exception) {
        }

        $this->createProtectedFile($filename);
        $this->createImmutableFile($filename);
        $this->assertFileExists($filename);
        $this->assertDirectoryExists($oldDirectoryPath);
        $this->assertDirectoryDoesNotExist($newDirectoryPath);

        // When & Then
        (new Directory($oldDirectoryPath))->copy($newDirectoryPath);
    }
}
