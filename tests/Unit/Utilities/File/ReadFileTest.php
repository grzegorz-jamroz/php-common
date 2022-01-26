<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\File\CreateFileIfNotExists;
use Ifrost\Common\Utilities\File\DeleteFile;
use Ifrost\Common\Utilities\File\ReadFile;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class ReadFileTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        (new CreateDirectoryIfNotExists(DATA_DIRECTORY))->execute();
    }

    public function testShouldReturnEmptyStringWhenFileIsEmpty()
    {
        // Expect & Given
        $filename = sprintf('%s/file/read-file/empty.txt', DATA_DIRECTORY);
        (new DeleteFile($filename))->execute();
        (new CreateFileIfNotExists($filename))->execute();
        $this->assertFileExists($filename);

        // When
        $content = (new ReadFile($filename))->acquire();

        //Then
        $this->assertEquals('', $content);
    }

    public function testShouldReturnStringAsContent()
    {
        // Expect & Given
        $filename = sprintf('%s/file/read-file/empty.txt', DATA_DIRECTORY);
        (new DeleteFile($filename))->execute();
        (new CreateFileIfNotExists($filename, 'hello world'))->execute();
        $this->assertFileExists($filename);

        // When
        $content = (new ReadFile($filename))->acquire();

        //Then
        $this->assertEquals('hello world', $content);
    }

    public function testShouldThrowRuntimeExceptionWhenFileDoesNotExist()
    {
        // Expect & Given
        $filename = sprintf('%s/file/read-file/sample_%s.txt', DATA_DIRECTORY, time());
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable to read file. File %s not exist.', $filename));
        $this->assertFileDoesNotExist($filename);

        // When & Then
        (new ReadFile($filename))->acquire();
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
        $this->expectExceptionMessage(sprintf('Unable to read content of file %s.', $filename));
        $this->assertFileExists($filename);

        // When & Then
        (new ReadFile($filename))->acquire();
    }
}
