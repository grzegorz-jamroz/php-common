<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\File\CreateFileIfNotExists;
use Ifrost\Common\Utilities\File\DeleteFile;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class DeleteFileTest extends TestCase
{
    use TestUtils;

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

    /**
     * it probably only works on ext2/ext3/ext4 filesystems.
     */
    public function testShouldThrowRuntimeExceptionWhenUnableToDeleteFile()
    {
        $this->endTestIfWindowsOs($this);
        $this->endTestIfEnvMissing($this, ['SUDOER_PASSWORD']);

        // Expect & Given
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/Unable remove file/');
        $filename = sprintf('%s/immutable_file.txt', TESTS_DATA_DIRECTORY);
        $this->createImmutableFile($filename);
        $this->assertFileExists($filename);

        // When & Then
        (new DeleteFile($filename))->execute();
    }
}
