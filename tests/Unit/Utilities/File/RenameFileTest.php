<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\File\CreateFileIfNotExists;
use Ifrost\Common\Utilities\File\DeleteFile;
use Ifrost\Common\Utilities\File\RenameFile;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class RenameFileTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        (new CreateDirectoryIfNotExists(DATA_DIRECTORY))->execute();
    }

    public function testShouldRenameFileInsideSameDirectory()
    {
        // Expect & Given
        $oldFilename = sprintf('%s/file/rename-file/exists_old.txt', DATA_DIRECTORY);
        $newFilename = sprintf('%s/file/rename-file/exists_new.txt', DATA_DIRECTORY);
        try {
            (new CreateFileIfNotExists($oldFilename))->execute();
        } catch (\Exception) {
        }
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
        (new CreateFileIfNotExists($oldFilename))->execute();
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
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/Old file does not exist./');
        $oldFilename = sprintf('%s/file/rename-file/exists_old.txt', DATA_DIRECTORY);
        $newFilename = sprintf('%s/file/rename-file/exists_new.txt', DATA_DIRECTORY);
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
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/New file already exists./');
        $oldFilename = sprintf('%s/file/rename-file/exists_old.txt', DATA_DIRECTORY);
        $newFilename = sprintf('%s/file/rename-file/exists_new.txt', DATA_DIRECTORY);
        (new CreateFileIfNotExists($oldFilename))->execute();
        (new CreateFileIfNotExists($newFilename))->execute();
        $this->assertFileExists($oldFilename);
        $this->assertFileExists($newFilename);

        // When & Then
        (new RenameFile($oldFilename, $newFilename))->execute();
    }

    /*
     * immutable_file.txt should be created with command `sudo chattr +i immutable_file.txt`
     * it probably only works on ext2/ext3/ext4 filesystems but I didn't have better idea how to test it
     */
    public function testShouldThrowRuntimeExceptionWhenUnableToDeleteFile()
    {
        $this->endTestIfWindowsOs($this);
        $this->endTestIfEnvMissing($this, ['PASSWORD']);

        // Expect & Given
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/Unable rename file/');
        $oldFilename = sprintf('%s/immutable_file.txt', TESTS_DATA_DIRECTORY);
        $newFilename = sprintf('%s/immutable_file_new.txt', TESTS_DATA_DIRECTORY);
        $this->assertFileExists($oldFilename);
        $this->assertFileDoesNotExist($newFilename);

        // When & Then
        (new RenameFile($oldFilename, $newFilename))->execute();
    }
}
