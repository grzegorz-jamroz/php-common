<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\File\File;
use Ifrost\Common\Utilities\File\TextFile;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class GetFileNumberOfLinesTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldReturnIntegerOneWhenFileIsEmpty()
    {
        // Expect & Given
        $filename = sprintf('%s/file/get-file-number-of-lines/test.txt', DATA_DIRECTORY);
        (new File($filename))->delete();
        $this->createFileIfNotExists($filename);
        $this->assertFileExists($filename);
        $this->assertEquals('', (new TextFile($filename))->read());

        // When & Then
        $this->assertEquals(1, (new File($filename))->countLines());
    }

    public function testShouldReturnIntegerOneWhenFileContainsOnlyOneLineWithString()
    {
        // Expect & Given
        $filename = sprintf('%s/file/get-file-number-of-lines/test.txt', DATA_DIRECTORY);
        (new File($filename))->delete();
        $this->createFileIfNotExists($filename, 'hello world');
        $this->assertFileExists($filename);
        $this->assertEquals('hello world', (new TextFile($filename))->read());

        // When & Then
        $this->assertEquals(1, (new File($filename))->countLines());
    }

    public function testShouldReturnIntegerTwoWhenFileContainsFirstLineEmptyAndSecondLineWithString()
    {
        // Expect & Given
        $filename = sprintf('%s/file/get-file-number-of-lines/test.txt', DATA_DIRECTORY);
        (new File($filename))->delete();
        $this->createFileIfNotExists($filename, "\n");
        $this->assertFileExists($filename);
        (new TextFile($filename))->write('line two');

        // When & Then
        $this->assertEquals(2, (new File($filename))->countLines());
        $this->assertEquals("\n", (new File($filename))->getLine(1));
        $this->assertEquals('line two', (new File($filename))->getLine(2));
    }

    public function testShouldReturnIntegerFourWhenFileContainsFourLines()
    {
        // Expect & Given
        $filename = sprintf('%s/file/get-file-number-of-lines/test.txt', DATA_DIRECTORY);
        (new File($filename))->delete();
        $this->createFileIfNotExists($filename);
        $this->assertFileExists($filename);
        (new TextFile($filename))->write("line one\n");
        (new TextFile($filename))->write('hello from');
        (new TextFile($filename))->write(" line two\n\n");
        (new TextFile($filename))->write(' line four');

        // When & Then
        $this->assertEquals(4, (new File($filename))->countLines());
        $this->assertEquals("line one\n", (new File($filename))->getLine(1));
        $this->assertEquals("hello from line two\n", (new File($filename))->getLine(2));
        $this->assertEquals("\n", (new File($filename))->getLine(3));
        $this->assertEquals(' line four', (new File($filename))->getLine(4));
    }

    public function testShouldThrowRuntimeExceptionWhenFileDoesNotExists()
    {
        // Expect & Given
        $filename = sprintf('%s/file/get-file-number-of-lines/test.txt', DATA_DIRECTORY);
        (new File($filename))->delete();
        $this->assertFileDoesNotExist($filename);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable to read file. File %s not exist.', $filename));

        // When & Then
        (new File($filename))->countLines();
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
        (new File($filename))->countLines();
    }
}
