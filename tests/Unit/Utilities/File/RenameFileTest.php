<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\File\DeleteFile;
use Ifrost\Common\Utilities\File\Exception\FileAlreadyExists;
use Ifrost\Common\Utilities\File\Exception\FileNotExist;
use Ifrost\Common\Utilities\File\RenameFile;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class RenameFileTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldRenameFileInsideSameDirectory()
    {
        // Expect & Given
        $oldFilename = sprintf('%s/file/rename-file/exists_old.txt', DATA_DIRECTORY);
        $newFilename = sprintf('%s/file/rename-file/exists_new.txt', DATA_DIRECTORY);
        $this->createFileIfNotExists($oldFilename);
        (new DeleteFile($newFilename))->execute();
        $this->assertFileExists($oldFilename);
        $this->assertFileDoesNotExist($newFilename);

        // When
        (new RenameFile($oldFilename, $newFilename))->execute();

        // Then
        $this->assertFileDoesNotExist($oldFilename);
        $this->assertFileExists($newFilename);
    }

    public function testShouldRenameFileAndMoveToNewNotExistedDirectory()
    {
        // Expect & Given
        $oldFilename = sprintf('%s/file/rename-file/exists_old.txt', DATA_DIRECTORY);
        $newFilename = sprintf('%s/file/rename-file/new_directory/exists_new.txt', DATA_DIRECTORY);
        $this->createFileIfNotExists($oldFilename);
        (new DeleteFile($newFilename))->execute();
        $this->assertFileExists($oldFilename);
        $this->assertFileDoesNotExist($newFilename);

        // When
        (new RenameFile($oldFilename, $newFilename))->execute();

        // Then
        $this->assertFileDoesNotExist($oldFilename);
        $this->assertFileExists($newFilename);
    }

    public function testShouldThrowRuntimeExceptionWhenOldFileDoesNotExist()
    {
        // Expect & Given
        $oldFilename = sprintf('%s/file/rename-file/exists_old.txt', DATA_DIRECTORY);
        $newFilename = sprintf('%s/file/rename-file/exists_new.txt', DATA_DIRECTORY);
        $this->expectException(FileNotExist::class);
        $this->expectExceptionMessage(sprintf('Unable rename file "%s". Old file does not exist.', $oldFilename));
        (new DeleteFile($oldFilename))->execute();
        (new DeleteFile($newFilename))->execute();
        $this->assertFileDoesNotExist($oldFilename);
        $this->assertFileDoesNotExist($newFilename);

        // When & Then
        (new RenameFile($oldFilename, $newFilename))->execute();
    }

    public function testShouldThrowRuntimeExceptionWhenNewFileExists()
    {
        // Expect & Given
        $oldFilename = sprintf('%s/file/rename-file/exists_old.txt', DATA_DIRECTORY);
        $newFilename = sprintf('%s/file/rename-file/exists_new.txt', DATA_DIRECTORY);
        $this->expectException(FileAlreadyExists::class);
        $this->expectExceptionMessage(sprintf('Unable rename file "%s". New file already exists.', $newFilename));
        $this->createFileIfNotExists($oldFilename);
        $this->createFileIfNotExists($newFilename);
        $this->assertFileExists($oldFilename);
        $this->assertFileExists($newFilename);

        // When & Then
        (new RenameFile($oldFilename, $newFilename))->execute();
    }

    /**
     * it probably only works on ext2/ext3/ext4 filesystems.
     */
    public function testShouldThrowRuntimeExceptionWhenUnableToDeleteFile()
    {
        $this->endTestIfWindowsOs($this);
        $this->endTestIfEnvMissing($this, ['SUDOER_PASSWORD']);

        // Expect & Given
        $oldFilename = sprintf('%s/immutable_file.txt', TESTS_DATA_DIRECTORY);
        $newFilename = sprintf('%s/immutable_file_new.txt', TESTS_DATA_DIRECTORY);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable rename file "%s". ', $oldFilename));
        $this->createImmutableFile($oldFilename);
        $this->assertFileExists($oldFilename);
        $this->assertFileDoesNotExist($newFilename);

        // When & Then
        (new RenameFile($oldFilename, $newFilename))->execute();
    }
}
