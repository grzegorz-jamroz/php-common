<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\File\File;
use Ifrost\Common\Utilities\File\GetFileLine;
use Ifrost\Common\Utilities\File\TextFile;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class GetFileLineTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldReturnEmptyStringWhenFileIsEmpty()
    {
        // Expect & Given
        $filename = sprintf('%s/file/get-file-number-of-lines/test.txt', DATA_DIRECTORY);
        (new File($filename))->delete();
        $this->createFileIfNotExists($filename);
        $this->assertFileExists($filename);
        $this->assertEquals('', (new TextFile($filename))->read());

        // When & Then
        $this->assertEquals('', (new GetFileLine($filename, 1))->acquire());
    }

    public function testShouldReturnStringForFirstLine()
    {
        // Expect & Given
        $filename = sprintf('%s/file/get-file-number-of-lines/test.txt', DATA_DIRECTORY);
        (new File($filename))->delete();
        $this->createFileIfNotExists($filename, "hello\n");
        $this->assertFileExists($filename);

        // When & Then
        $this->assertEquals("hello\n", (new GetFileLine($filename, 1))->acquire());
        $this->assertEquals('', (new GetFileLine($filename, 2))->acquire());
    }

    public function testShouldReturnProperStringsForEachLine()
    {
        // Expect & Given
        $filename = sprintf('%s/file/get-file-number-of-lines/test.txt', DATA_DIRECTORY);
        (new File($filename))->delete();
        $this->createFileIfNotExists($filename, "hello\n\ngood\nmorning");
        $this->assertFileExists($filename);

        // When & Then
        $this->assertEquals("hello\n", (new GetFileLine($filename, 1))->acquire());
        $this->assertEquals("\n", (new GetFileLine($filename, 2))->acquire());
        $this->assertEquals("good\n", (new GetFileLine($filename, 3))->acquire());
        $this->assertEquals('morning', (new GetFileLine($filename, 4))->acquire());
    }


    public function testShouldThrowRuntimeExceptionWhenFileDoesNotExists()
    {
        // Expect & Given
        $filename = sprintf('%s/file/get-file-line/test.txt', DATA_DIRECTORY);
        (new File($filename))->delete();
        $this->assertFileDoesNotExist($filename);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable to read file. File %s not exist.', $filename));

        // When & Then
        (new GetFileLine($filename, 1))->acquire();
    }

    /**
     * it probably only works on ext2/ext3/ext4 filesystems.
     */
    public function testShouldThrowRuntimeExceptionWhenUnableToReadFile()
    {
        $this->endTestIfWindowsOs($this);
        $this->endTestIfEnvMissing($this, ['SUDOER_PASSWORD']);

        // Expect & Given
        $filename = sprintf('%s/protected.txt', TESTS_DATA_DIRECTORY);
        $this->createProtectedFile($filename);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable to read file %s.', $filename));
        $this->assertFileExists($filename);

        // When & Then
        (new GetFileLine($filename, 1))->acquire();
    }
}
