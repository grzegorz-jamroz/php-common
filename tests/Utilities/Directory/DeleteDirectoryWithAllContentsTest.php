<?php
declare(strict_types=1);

namespace Tests\Utilities\Directory;

use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\Directory\DeleteDirectoryWithAllContents;
use Ifrost\Common\Utilities\File\CreateFileIfNotExists;
use PHPUnit\Framework\TestCase;

class DeleteDirectoryWithAllContentsTest extends TestCase
{
    public function testShouldRemoveEmptyDirectory()
    {
        // Given
        $directoryPath = sprintf('%s/empty-directory', DATA_DIRECTORY);
        (new CreateDirectoryIfNotExists($directoryPath))->execute();

        // When
        (new DeleteDirectoryWithAllContents(DATA_DIRECTORY))->execute();

        // Then
        $this->assertEquals(false, file_exists($directoryPath));
    }

    public function testShouldThrowInvalidArgumentExceptionWhenPathIsFile()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $directoryPath = sprintf('%s/test.txt', DATA_DIRECTORY);
        (new CreateFileIfNotExists($directoryPath))->execute();

        // When
        (new DeleteDirectoryWithAllContents($directoryPath))->execute();
    }
}
