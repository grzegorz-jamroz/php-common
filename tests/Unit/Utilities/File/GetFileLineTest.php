<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\File\CreateFileIfNotExists;
use Ifrost\Common\Utilities\File\DeleteFile;
use Ifrost\Common\Utilities\File\GetFileLine;
use Ifrost\Common\Utilities\File\ReadFile;
use PHPUnit\Framework\TestCase;

class GetFileLineTest extends TestCase
{
    protected function setUp(): void
    {
        (new CreateDirectoryIfNotExists(DATA_DIRECTORY))->execute();
    }

    public function testShouldReturnEmptyStringWhenFileIsEmpty()
    {
        // Expect & Given
        $filename = sprintf('%s/file/get-file-number-of-lines/test.txt', DATA_DIRECTORY);
        (new DeleteFile($filename))->execute();
        (new CreateFileIfNotExists($filename))->execute();
        $this->assertFileExists($filename);
        $this->assertEquals('', (new ReadFile($filename))->acquire());

        // When & Then
        $this->assertEquals('', (new GetFileLine($filename, 1))->acquire());
    }

    public function testShouldReturnStringForFirstLine()
    {
        // Expect & Given
        $filename = sprintf('%s/file/get-file-number-of-lines/test.txt', DATA_DIRECTORY);
        (new DeleteFile($filename))->execute();
        (new CreateFileIfNotExists($filename, "hello\n"))->execute();
        $this->assertFileExists($filename);

        // When & Then
        $this->assertEquals("hello\n", (new GetFileLine($filename, 1))->acquire());
        $this->assertEquals('', (new GetFileLine($filename, 2))->acquire());
    }

    public function testShouldReturnProperStringsForEachLine()
    {
        // Expect & Given
        $filename = sprintf('%s/file/get-file-number-of-lines/test.txt', DATA_DIRECTORY);
        (new DeleteFile($filename))->execute();
        (new CreateFileIfNotExists($filename, "hello\n\ngood\nmorning"))->execute();
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
        (new DeleteFile($filename))->execute();
        $this->assertFileDoesNotExist($filename);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable to read file. File %s not exist.', $filename));

        // When & Then
        (new GetFileLine($filename, 1))->acquire();
    }

    /*
     * protected.txt should be created with command `chmod 000 protected.txt`
     * it probably only works on ext2/ext3/ext4 filesystems but I didn't have better idea how to test it
     */
    public function testShouldThrowRuntimeExceptionWhenUnableToReadFile()
    {
        // Expect & Given
        $filename = sprintf('%s/protected.txt', TESTS_DATA_DIRECTORY);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable to read file %s.', $filename));
        $this->assertFileExists($filename);

        // When & Then
        (new GetFileLine($filename, 1))->acquire();
    }
}
