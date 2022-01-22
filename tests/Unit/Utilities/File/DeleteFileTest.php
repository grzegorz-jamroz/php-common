<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\File\CreateFileIfNotExists;
use Ifrost\Common\Utilities\File\DeleteFile;
use PHPUnit\Framework\TestCase;

class DeleteFileTest extends TestCase
{
    protected function setUp(): void
    {
        (new CreateDirectoryIfNotExists(DATA_DIRECTORY))->execute();
    }

    public function testShouldLetNothingHappenWhenFileNotExists()
    {
        // Expect & Given
        $filename = sprintf('%s/file/delete-file/test/not_exists.txt', DATA_DIRECTORY);
        $this->assertFileDoesNotExist($filename);

        // When
        try {
            (new DeleteFile($filename))->execute();
        } catch(\Exception) {
            $this->assertEquals(1, 1);
        }

        // Then
        $this->assertEquals(1, $this->getCount());
    }

    public function testShouldDeleteFileWhenItExists()
    {
        // Expect & Given
        $filename = sprintf('%s/file/delete-file/file.txt', DATA_DIRECTORY);
        (new CreateFileIfNotExists($filename))->execute();
        $this->assertFileExists($filename);

        // When
        (new DeleteFile($filename))->execute();

        // Then
        $this->assertFileDoesNotExist($filename);
    }

    /*
     * immutable_file.txt should be created with command `sudo chattr +i immutable_file.txt`
     * it probably only works on ext2/ext3/ext4 filesystems but I didn't have better idea how to test it
     */
    public function testShouldThrowRuntimeExceptionWhenUnableToDeleteFile()
    {
        // Expect & Given
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/Unable remove file/');
        $filename = sprintf('%s/immutable_file.txt', TESTS_DATA_DIRECTORY);
        $this->assertFileExists($filename);

        // When & Then
        (new DeleteFile($filename))->execute();
    }
}
