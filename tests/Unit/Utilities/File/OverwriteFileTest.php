<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\File\DeleteFile;
use Ifrost\Common\Utilities\File\OverwriteFile;
use Ifrost\Common\Utilities\File\TextFile;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class OverwriteFileTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldOverwriteExistedFileWithEmptyString()
    {
        // Expect & Given
        $filename = sprintf('%s/file/overwrite-file/test.txt', DATA_DIRECTORY);
        $this->createFileIfNotExists($filename);
        file_put_contents($filename, 'something');
        $this->assertFileExists($filename);
        $this->assertEquals('something', file_get_contents($filename));

        // When
        (new TextFile($filename))->overwrite('');

        // Then
        $this->assertEquals('', file_get_contents($filename));
    }

    public function testShouldOverwriteExistedFileWithGivenContent()
    {
        // Expect & Given
        $filename = sprintf('%s/file/overwrite-file/test2.txt', DATA_DIRECTORY);
        $this->createFileIfNotExists($filename);
        file_put_contents($filename, 'something2');
        $this->assertFileExists($filename);
        $this->assertEquals('something2', file_get_contents($filename));

        // When
        (new TextFile($filename))->overwrite('foo');

        // Then
        $this->assertEquals('foo', file_get_contents($filename));
    }

    public function testShouldCreateNotExistedFileAndWriteGivenContent()
    {
        // Expect & Given
        $filename = sprintf('%s/file/overwrite-file/test3.txt', DATA_DIRECTORY);
        (new DeleteFile($filename))->execute();
        $this->assertFileDoesNotExist($filename);

        // When
        (new TextFile($filename))->overwrite('hello');

        // Then
        $this->assertEquals('hello', file_get_contents($filename));
    }

    /**
     * it probably only works on ext2/ext3/ext4 filesystems.
     */
    public function testShouldThrowRuntimeExceptionWhenUnableToReadFile()
    {
        $this->endTestIfWindowsOs($this);
        $this->endTestIfEnvMissing($this, ['SUDOER_PASSWORD']);

        // Expect & Given
        $filename = sprintf('%s/immutable_file.txt', TESTS_DATA_DIRECTORY);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable to overwrite file "%s".', $filename));
        $this->createImmutableFile($filename);
        $this->assertFileExists($filename);

        // When & Then
        (new TextFile($filename))->overwrite('something');
    }

    /**
     * it probably only works on ext2/ext3/ext4 filesystems.
     */
    public function testShouldThrowRuntimeExceptionWhenTryingToWriteToReadOnlyFile()
    {
        $this->endTestIfWindowsOs($this);
        $this->endTestIfEnvMissing($this, ['SUDOER_PASSWORD']);

        // Expect & Given
        $filename = sprintf('%s/read-only.txt', TESTS_DATA_DIRECTORY);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable to overwrite file "%s".', $filename));
        $this->createReadOnlyFile($filename);
        $this->assertFileExists($filename);

        // When & Then
        (new TextFile($filename))->overwrite('something');
    }
}
