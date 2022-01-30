<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\File\File;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class DeleteFileTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldLetNothingHappenWhenFileNotExists()
    {
        // Expect & Given
        $filename = sprintf('%s/file/delete-file/test/not_exists.txt', DATA_DIRECTORY);
        $this->assertFileDoesNotExist($filename);

        // When
        try {
            (new File($filename))->delete();
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
        $this->createFileIfNotExists($filename);
        $this->assertFileExists($filename);

        // When
        (new File($filename))->delete();

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
        $filename = sprintf('%s/immutable_file.txt', TESTS_DATA_DIRECTORY);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable remove file "%s". ', $filename));
        $this->createImmutableFile($filename);
        $this->assertFileExists($filename);

        // When & Then
        (new File($filename))->delete();
    }
}
