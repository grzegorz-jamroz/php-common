<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\Directory;

use Ifrost\Common\Utilities\Directory\DeleteDirectoryWithAllContent;
use Ifrost\Common\Utilities\Directory\Exception\DirectoryAlreadyExists;
use Ifrost\Common\Utilities\Directory\Exception\DirectoryNotExist;
use Ifrost\Common\Utilities\Directory\RenameDirectory;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class RenameDirectoryTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldRenameDirectoryInsideSameDirectory()
    {
        // Expect & Given
        $oldDirectory = sprintf('%s/directory/rename-directory/exists_old', DATA_DIRECTORY);
        $newDirectory = sprintf('%s/directory/rename-directory/exists_new', DATA_DIRECTORY);
        $this->createDirectoryIfNotExists($oldDirectory);
        (new DeleteDirectoryWithAllContent($newDirectory))->execute();
        $this->assertDirectoryExists($oldDirectory);
        $this->assertDirectoryDoesNotExist($newDirectory);

        // When
        (new RenameDirectory($oldDirectory, $newDirectory))->execute();

        // Then
        $this->assertDirectoryDoesNotExist($oldDirectory);
        $this->assertDirectoryExists($newDirectory);
    }

    public function testShouldRenameDirectoryAndMoveToNewNotExistedDirectory()
    {
        // Expect & Given
        $oldDirectory = sprintf('%s/directory/rename-directory/exists_old', DATA_DIRECTORY);
        $newDirectoryLocation = sprintf('%s/directory/rename-directory/new_directory', DATA_DIRECTORY);
        $newDirectory = sprintf('%s/directory/rename-directory/new_directory/exists_new', DATA_DIRECTORY);
        $this->createDirectoryIfNotExists($oldDirectory);
        (new DeleteDirectoryWithAllContent($newDirectoryLocation))->execute();
        $this->assertDirectoryExists($oldDirectory);
        $this->assertDirectoryDoesNotExist($newDirectory);

        // When
        (new RenameDirectory($oldDirectory, $newDirectory))->execute();

        // Then
        $this->assertDirectoryDoesNotExist($oldDirectory);
        $this->assertDirectoryExists($newDirectory);
    }

    public function testShouldThrowRuntimeExceptionWhenOldDirectoryDoesNotExist()
    {
        // Expect & Given
        $oldDirectory = sprintf('%s/directory/rename-directory/exists_old.txt', DATA_DIRECTORY);
        $newDirectory = sprintf('%s/directory/rename-directory/exists_new.txt', DATA_DIRECTORY);
        $this->expectException(DirectoryNotExist::class);
        $this->expectExceptionMessage(sprintf('Unable rename file "%s". Old file does not exist.', $oldDirectory));
        (new DeleteDirectoryWithAllContent($oldDirectory))->execute();
        (new DeleteDirectoryWithAllContent($newDirectory))->execute();
        $this->assertDirectoryDoesNotExist($oldDirectory);
        $this->assertDirectoryDoesNotExist($newDirectory);

        // When & Then
        (new RenameDirectory($oldDirectory, $newDirectory))->execute();
    }

    public function testShouldThrowRuntimeExceptionWhenNewDirectoryExists()
    {
        // Expect & Given
        $oldDirectory = sprintf('%s/directory/rename-directory/exists_old.txt', DATA_DIRECTORY);
        $newDirectory = sprintf('%s/directory/rename-directory/exists_new.txt', DATA_DIRECTORY);
        $this->expectException(DirectoryAlreadyExists::class);
        $this->expectExceptionMessage(sprintf('Unable rename file "%s". New file already exists.', $newDirectory));
        $this->createDirectoryIfNotExists($oldDirectory);
        $this->createDirectoryIfNotExists($newDirectory);
        $this->assertDirectoryExists($oldDirectory);
        $this->assertDirectoryExists($newDirectory);

        // When & Then
        (new RenameDirectory($oldDirectory, $newDirectory))->execute();
    }

    /**
     * it probably only works on ext2/ext3/ext4 filesystems.
     */
    public function testShouldThrowRuntimeExceptionWhenUnableToDeleteDirectory()
    {
        $this->endTestIfWindowsOs($this);
        $this->endTestIfEnvMissing($this, ['SUDOER_PASSWORD']);

        // Expect & Given
        $oldDirectory = sprintf('%s/immutable_file.txt', TESTS_DATA_DIRECTORY);
        $newDirectory = sprintf('%s/immutable_file_new.txt', TESTS_DATA_DIRECTORY);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable rename file "%s". ', $oldDirectory));
        $this->createImmutableDirectory($oldDirectory);
        $this->assertDirectoryExists($oldDirectory);
        $this->assertDirectoryDoesNotExist($newDirectory);

        // When & Then
        (new RenameDirectory($oldDirectory, $newDirectory))->execute();
    }
}
