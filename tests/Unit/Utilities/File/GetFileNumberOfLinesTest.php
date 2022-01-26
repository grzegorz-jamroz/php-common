<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\File\CreateFileIfNotExists;
use Ifrost\Common\Utilities\File\DeleteFile;
use Ifrost\Common\Utilities\File\GetFileLine;
use Ifrost\Common\Utilities\File\GetFileNumberOfLines;
use Ifrost\Common\Utilities\File\ReadFile;
use Ifrost\Common\Utilities\File\WriteFile;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class GetFileNumberOfLinesTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        (new CreateDirectoryIfNotExists(DATA_DIRECTORY))->execute();
    }

    public function testShouldReturnIntegerOneWhenFileIsEmpty()
    {
        // Expect & Given
        $filename = sprintf('%s/file/get-file-number-of-lines/test.txt', DATA_DIRECTORY);
        (new DeleteFile($filename))->execute();
        (new CreateFileIfNotExists($filename))->execute();
        $this->assertFileExists($filename);
        $this->assertEquals('', (new ReadFile($filename))->acquire());

        // When & Then
        $this->assertEquals(1, (new GetFileNumberOfLines($filename))->acquire());
    }

    public function testShouldReturnIntegerOneWhenFileContainsOnlyOneLineWithString()
    {
        // Expect & Given
        $filename = sprintf('%s/file/get-file-number-of-lines/test.txt', DATA_DIRECTORY);
        (new DeleteFile($filename))->execute();
        (new CreateFileIfNotExists($filename, 'hello world'))->execute();
        $this->assertFileExists($filename);
        $this->assertEquals('hello world', (new ReadFile($filename))->acquire());

        // When & Then
        $this->assertEquals(1, (new GetFileNumberOfLines($filename))->acquire());
    }

    public function testShouldReturnIntegerTwoWhenFileContainsFirstLineEmptyAndSecondLineWithString()
    {
        // Expect & Given
        $filename = sprintf('%s/file/get-file-number-of-lines/test.txt', DATA_DIRECTORY);
        (new DeleteFile($filename))->execute();
        (new CreateFileIfNotExists($filename, "\n"))->execute();
        $this->assertFileExists($filename);
        (new WriteFile($filename, 'line two'))->execute();

        // When & Then
        $this->assertEquals(2, (new GetFileNumberOfLines($filename))->acquire());
        $this->assertEquals("\n", (new GetFileLine($filename, 1))->acquire());
        $this->assertEquals('line two', (new GetFileLine($filename, 2))->acquire());
    }

    public function testShouldReturnIntegerFourWhenFileContainsFourLines()
    {
        // Expect & Given
        $filename = sprintf('%s/file/get-file-number-of-lines/test.txt', DATA_DIRECTORY);
        (new DeleteFile($filename))->execute();
        (new CreateFileIfNotExists($filename))->execute();
        $this->assertFileExists($filename);
        (new WriteFile($filename, "line one\n"))->execute();
        (new WriteFile($filename, 'hello from'))->execute();
        (new WriteFile($filename, " line two\n\n"))->execute();
        (new WriteFile($filename, " line four"))->execute();

        // When & Then
        $this->assertEquals(4, (new GetFileNumberOfLines($filename))->acquire());
        $this->assertEquals("line one\n", (new GetFileLine($filename, 1))->acquire());
        $this->assertEquals("hello from line two\n", (new GetFileLine($filename, 2))->acquire());
        $this->assertEquals("\n", (new GetFileLine($filename, 3))->acquire());
        $this->assertEquals(' line four', (new GetFileLine($filename, 4))->acquire());
    }

    public function testShouldThrowRuntimeExceptionWhenFileDoesNotExists()
    {
        // Expect & Given
        $filename = sprintf('%s/file/get-file-number-of-lines/test.txt', DATA_DIRECTORY);
        (new DeleteFile($filename))->execute();
        $this->assertFileDoesNotExist($filename);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable to read file. File %s not exist.', $filename));

        // When & Then
        (new GetFileNumberOfLines($filename))->acquire();
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
        (new GetFileNumberOfLines($filename))->acquire();
    }
}
