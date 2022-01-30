<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\File\File;
use Ifrost\Common\Utilities\File\TextFile;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class WriteFileTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldShouldLetNothingHappenWhenWriteEmptyStringsToExistedFile()
    {
        // Expect & Given
        $filename = sprintf('%s/file/write-file/test.txt', DATA_DIRECTORY);
        (new File($filename))->delete();
        $this->createFileIfNotExists($filename, 'something');
        $this->assertFileExists($filename);
        $this->assertEquals('something', file_get_contents($filename));

        // When
        (new TextFile($filename))->write('');

        // Then
        $this->assertEquals('something', file_get_contents($filename));
    }

    public function testShouldAddSingleSpaceToExistedFile()
    {
        // Expect & Given
        $filename = sprintf('%s/file/write-file/test.txt', DATA_DIRECTORY);
        (new File($filename))->delete();
        $this->createFileIfNotExists($filename, 'something');
        $this->assertFileExists($filename);
        $this->assertEquals('something', file_get_contents($filename));

        // When
        (new TextFile($filename))->write(' ');

        // Then
        $this->assertEquals('something ', file_get_contents($filename));
    }

    public function testShouldCreateFileWhichContainsThreeLinesAndLastLineIsNotEmpty()
    {
        // Expect & Given
        $filename = sprintf('%s/file/write-file/test.txt', DATA_DIRECTORY);
        (new File($filename))->delete();
        $this->assertFileDoesNotExist($filename);

        // When
        (new TextFile($filename))->write("line one\n");
        (new TextFile($filename))->write("line two\n");
        (new TextFile($filename))->write('line three');

        // Then
        $this->assertEquals(3, (new File($filename))->countLines());
        $this->assertEquals('line three', (new File($filename))->getLine(3));
    }

    public function testShouldCreateFileWhichContainsThreeLinesAndLastLineIsEmpty()
    {
        // Expect & Given
        $filename = sprintf('%s/file/write-file/test.txt', DATA_DIRECTORY);
        (new File($filename))->delete();
        $this->assertFileDoesNotExist($filename);

        // When
        (new TextFile($filename))->write("line one\n");
        (new TextFile($filename))->write("line two\n");
        (new TextFile($filename))->write("line three\n");

        // Then
        $this->assertEquals(4, (new File($filename))->countLines());
        $this->assertEquals('', (new File($filename))->getLine(4));
    }

    public function testShouldAddTwoNewStringsToExistedFile()
    {
        // Expect & Given
        $filename = sprintf('%s/file/write-file/test.txt', DATA_DIRECTORY);
        (new File($filename))->delete();
        $this->createFileIfNotExists($filename, 'something');
        $this->assertFileExists($filename);
        $this->assertEquals('something', file_get_contents($filename));

        // When
        (new TextFile($filename))->write('new text 1');
        (new TextFile($filename))->write('new text 2');

        // Then
        $this->assertEquals('somethingnew text 1new text 2', file_get_contents($filename));
    }

    public function testShouldCreateNotExistedFileAndWriteGivenContent()
    {
        // Expect & Given
        $filename = sprintf('%s/file/write-file/test.txt', DATA_DIRECTORY);
        (new File($filename))->delete();
        $this->assertFileDoesNotExist($filename);

        // When
        (new TextFile($filename))->write('hello');

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
        $this->createImmutableFile($filename);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable to write to file %s.', $filename));
        $this->assertFileExists($filename);

        // When & Then
        (new TextFile($filename))->write('new text');
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
        $this->createReadOnlyFile($filename);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable to write to file %s.', $filename));
        $this->assertFileExists($filename);

        // When & Then
        (new TextFile($filename))->write('new text');
    }
}
