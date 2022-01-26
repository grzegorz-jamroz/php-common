<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\File\CreateFileIfNotExists;
use Ifrost\Common\Utilities\File\DeleteFile;
use Ifrost\Common\Utilities\File\OverwriteFile;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class OverwriteFileTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        (new CreateDirectoryIfNotExists(DATA_DIRECTORY))->execute();
    }

    public function testShouldOverwriteExistedFileWithEmptyString()
    {
        // Expect & Given
        $filename = sprintf('%s/file/overwrite-file/test.txt', DATA_DIRECTORY);
        (new CreateFileIfNotExists($filename))->execute();
        file_put_contents($filename, 'something');
        $this->assertFileExists($filename);
        $this->assertEquals('something', file_get_contents($filename));

        // When
        (new OverwriteFile($filename, ''))->execute();

        // Then
        $this->assertEquals('', file_get_contents($filename));
    }

    public function testShouldOverwriteExistedFileWithGivenContent()
    {
        // Expect & Given
        $filename = sprintf('%s/file/overwrite-file/test2.txt', DATA_DIRECTORY);
        (new CreateFileIfNotExists($filename))->execute();
        file_put_contents($filename, 'something2');
        $this->assertFileExists($filename);
        $this->assertEquals('something2', file_get_contents($filename));

        // When
        (new OverwriteFile($filename, 'foo'))->execute();

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
        (new OverwriteFile($filename, 'hello'))->execute();

        // Then
        $this->assertEquals('hello', file_get_contents($filename));
    }

    /*
     * immutable_file.txt should be created with command `sudo chattr +i immutable_file.txt`
     * it probably only works on ext2/ext3/ext4 filesystems but I didn't have better idea how to test it
     */
    public function testShouldThrowRuntimeExceptionWhenUnableToReadFile()
    {
        $this->endTestIfWindowsOs($this);
        $this->endTestIfEnvMissing($this, ['PASSWORD']);

        // Expect & Given
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/Unable to overwrite file/');
        $filename = sprintf('%s/immutable_file.txt', TESTS_DATA_DIRECTORY);
        $this->assertFileExists($filename);

        // When & Then
        (new OverwriteFile($filename, 'something'))->execute();
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
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/Unable to overwrite file/');
        $filename = sprintf('%s/read-only.txt', TESTS_DATA_DIRECTORY);
        $this->assertFileExists($filename);

        // When & Then
        (new OverwriteFile($filename, 'something'))->execute();
    }
}
