<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\Directory;

use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\Directory\GetFilesFromDirectory;
use PHPUnit\Framework\TestCase;

class GetFilesFromDirectoryTest extends TestCase
{
    protected function setUp(): void
    {
        (new CreateDirectoryIfNotExists(DATA_DIRECTORY))->execute();
    }

    public function testShouldReturnEmptyArrayWhenDirectoryIsEmpty()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/get-files-from-directory/empty_directory', DATA_DIRECTORY);
        (new CreateDirectoryIfNotExists($directoryPath))->execute();
        $this->assertDirectoryExists($directoryPath);

        // When
        $files = (new GetFilesFromDirectory($directoryPath))->acquire();

        // Then
        $this->assertEquals([], $files);
    }
}
