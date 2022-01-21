<?php

declare(strict_types=1);

namespace Tests\Utilities\File;

use Ifrost\Common\Utilities\File\CreateFileIfNotExists;
use Ifrost\Common\Utilities\File\DeleteFile;
use PHPUnit\Framework\TestCase;

class DeleteFileTest extends TestCase
{
    public function testShouldLetNothingHappenWhenFileNotExists()
    {
        // Expect & Given
        $filename = sprintf('%s/test/not_exists.txt', DATA_DIRECTORY);
        $this->assertFileDoesNotExist($filename);

        // When
        try {
            (new DeleteFile($filename))->execute();
        } catch(\Exception $e) {
            $this->assertEquals(1, 1);
        }

        // Then
        $this->assertEquals(1, $this->getCount());
    }

    public function testShouldDeleteFileWhenItExists()
    {
        // Expect & Given
        $filename = sprintf('%s/file.txt', DATA_DIRECTORY);
        (new CreateFileIfNotExists($filename))->execute();
        $this->assertFileExists($filename);

        // When
        (new DeleteFile($filename))->execute();

        // Then
        $this->assertFileDoesNotExist($filename);
    }
}
