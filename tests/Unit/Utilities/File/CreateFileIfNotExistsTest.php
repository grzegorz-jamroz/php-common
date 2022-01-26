<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\Directory\DeleteDirectoryWithAllContent;
use Ifrost\Common\Utilities\File\CreateFileIfNotExists;
use Ifrost\Common\Utilities\File\DeleteFile;
use Ifrost\Common\Utilities\File\Exception\FileAlreadyExists;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class CreateFileIfNotExistsTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        (new CreateDirectoryIfNotExists(DATA_DIRECTORY))->execute();
    }

    public function testShouldCreateFileInNotExistedDirectory()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/file/create-file/not_exists/folder', DATA_DIRECTORY);
        $filename = sprintf('%s/test.txt', $directoryPath);
        (new DeleteDirectoryWithAllContent($directoryPath))->execute();
        $this->assertDirectoryDoesNotExist($directoryPath);
        $this->assertFileDoesNotExist($filename);

        // When
        (new CreateFileIfNotExists($filename))->execute();

        // Then
        $this->assertFileExists($filename);
    }

    public function testShouldCreateFileInExistedDirectory()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/file/create-file/dir_exists', DATA_DIRECTORY);
        $filename = sprintf('%s/test.txt', $directoryPath);
        (new CreateDirectoryIfNotExists($directoryPath))->execute();
        (new DeleteFile($filename))->execute();
        $this->assertDirectoryExists($directoryPath);
        $this->assertFileDoesNotExist($filename);

        // When
        (new CreateFileIfNotExists($filename))->execute();

        // Then
        $this->assertFileExists($filename);
    }

    public function testShouldThrowFileAlreadyExistsWhenFileExists()
    {
        // Expect & Given
        $filename = sprintf('%s/exists.txt', TESTS_DATA_DIRECTORY);
        $this->expectException(FileAlreadyExists::class);
        $this->assertFileExists($filename);

        // When & Then
        (new CreateFileIfNotExists($filename))->execute();
    }

    /**
     * it probably only works on ext2/ext3/ext4 filesystems.
     */
    public function testShouldThrowRuntimeExceptionWhenUnableToCreateFile()
    {
        $this->endTestIfWindowsOs($this);
        $this->endTestIfEnvMissing($this, ['PASSWORD']);

        // Expect & Given
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/Unable to create file/');
        $directoryPath = sprintf('%s/immutable_dir', TESTS_DATA_DIRECTORY);
        $this->createImmutableDirectory($directoryPath);
        $filename = sprintf('%s/sample_%s.txt', $directoryPath, time());
        $this->assertDirectoryExists($directoryPath);
        $this->assertFileDoesNotExist($filename);

        // When & Then
        (new CreateFileIfNotExists($filename))->execute();
    }
}
