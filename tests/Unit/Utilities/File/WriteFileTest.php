<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\File\CreateFileIfNotExists;
use Ifrost\Common\Utilities\File\DeleteFile;
use Ifrost\Common\Utilities\File\GetFileLine;
use Ifrost\Common\Utilities\File\GetFileNumberOfLines;
use Ifrost\Common\Utilities\File\WriteFile;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class WriteFileTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        (new CreateDirectoryIfNotExists(DATA_DIRECTORY))->execute();
    }

    public function testShouldShouldLetNothingHappenWhenWriteEmptyStringsToExistedFile()
    {
        // Expect & Given
        $filename = sprintf('%s/file/write-file/test.txt', DATA_DIRECTORY);
        (new DeleteFile($filename))->execute();
        (new CreateFileIfNotExists($filename, 'something'))->execute();
        $this->assertFileExists($filename);
        $this->assertEquals('something', file_get_contents($filename));

        // When
        (new WriteFile($filename, ''))->execute();

        // Then
        $this->assertEquals('something', file_get_contents($filename));
    }

    public function testShouldAddSingleSpaceToExistedFile()
    {
        // Expect & Given
        $filename = sprintf('%s/file/write-file/test.txt', DATA_DIRECTORY);
        (new DeleteFile($filename))->execute();
        (new CreateFileIfNotExists($filename, 'something'))->execute();
        $this->assertFileExists($filename);
        $this->assertEquals('something', file_get_contents($filename));

        // When
        (new WriteFile($filename, ' '))->execute();

        // Then
        $this->assertEquals('something ', file_get_contents($filename));
    }

    public function testShouldCreateFileWhichContainsThreeLinesAndLastLineIsNotEmpty()
    {
        // Expect & Given
        $filename = sprintf('%s/file/write-file/test.txt', DATA_DIRECTORY);
        (new DeleteFile($filename))->execute();
        $this->assertFileDoesNotExist($filename);

        // When
        (new WriteFile($filename, "line one\n"))->execute();
        (new WriteFile($filename, "line two\n"))->execute();
        (new WriteFile($filename, 'line three'))->execute();

        // Then
        $this->assertEquals(3, (new GetFileNumberOfLines($filename))->acquire());
        $this->assertEquals('line three', (new GetFileLine($filename, 3))->acquire());
    }

    public function testShouldCreateFileWhichContainsThreeLinesAndLastLineIsEmpty()
    {
        // Expect & Given
        $filename = sprintf('%s/file/write-file/test.txt', DATA_DIRECTORY);
        (new DeleteFile($filename))->execute();
        $this->assertFileDoesNotExist($filename);

        // When
        (new WriteFile($filename, "line one\n"))->execute();
        (new WriteFile($filename, "line two\n"))->execute();
        (new WriteFile($filename, "line three\n"))->execute();

        // Then
        $this->assertEquals(4, (new GetFileNumberOfLines($filename))->acquire());
        $this->assertEquals('', (new GetFileLine($filename, 4))->acquire());
    }

    public function testShouldAddTwoNewStringsToExistedFile()
    {
        // Expect & Given
        $filename = sprintf('%s/file/write-file/test.txt', DATA_DIRECTORY);
        (new DeleteFile($filename))->execute();
        (new CreateFileIfNotExists($filename, 'something'))->execute();
        $this->assertFileExists($filename);
        $this->assertEquals('something', file_get_contents($filename));

        // When
        (new WriteFile($filename, 'new text 1'))->execute();
        (new WriteFile($filename, 'new text 2'))->execute();

        // Then
        $this->assertEquals('somethingnew text 1new text 2', file_get_contents($filename));
    }

    public function testShouldCreateNotExistedFileAndWriteGivenContent()
    {
        // Expect & Given
        $filename = sprintf('%s/file/write-file/test.txt', DATA_DIRECTORY);
        (new DeleteFile($filename))->execute();
        $this->assertFileDoesNotExist($filename);

        // When
        (new WriteFile($filename, 'hello'))->execute();

        // Then
        $this->assertEquals('hello', file_get_contents($filename));
    }

    /**
     * it probably only works on ext2/ext3/ext4 filesystems.
     */
    public function testShouldThrowRuntimeExceptionWhenUnableToReadFile()
    {
        $this->endTestIfWindowsOs($this);
        $this->endTestIfEnvMissing($this, ['PASSWORD']);

        // Expect & Given
        $filename = sprintf('%s/immutable_file.txt', TESTS_DATA_DIRECTORY);
        $this->createImmutableFile($filename);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable to write to file %s.', $filename));
        $this->assertFileExists($filename);

        // When & Then
        (new WriteFile($filename, 'new text'))->execute();
    }

    /*
     * read-only.txt should be created with command `chmod 444 read-only.txt`
     * it probably only works on ext2/ext3/ext4 filesystems but I didn't have better idea how to test it
     */
    public function testShouldThrowRuntimeExceptionWhenTryingToWriteToReadOnlyFile()
    {
        $this->endTestIfWindowsOs($this);
        $this->endTestIfEnvMissing($this, ['PASSWORD']);

        // Expect & Given
        $filename = sprintf('%s/read-only.txt', TESTS_DATA_DIRECTORY);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable to write to file %s.', $filename));
        $this->assertFileExists($filename);

        // When & Then
        (new WriteFile($filename, 'new text'))->execute();
    }
}
