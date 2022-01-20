<?php
declare(strict_types=1);

namespace Tests\Utilities\Directory;

use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\Directory\DeleteDirectoryWithAllContents;
use PHPUnit\Framework\TestCase;

class DeleteDirectoryWithAllContentsTest extends TestCase
{
    public function testShouldRemoveEmptyDirectory()
    {
        // Given
        $directoryPath = sprintf('%s/empty-directory', DATA_DIRECTORY);
        (new CreateDirectoryIfNotExists($directoryPath))->handle();

        // When
        (new DeleteDirectoryWithAllContents(DATA_DIRECTORY))->handle();

        // Then
        $this->assertEquals(false, file_exists($directoryPath));
    }

    public function testShouldThrowInvalidArgumentExceptionWhenPathIsFile()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $directoryPath = sprintf('%s/test.txt', DATA_DIRECTORY);

        // When
        (new DeleteDirectoryWithAllContents($directoryPath))->handle();
    }
}
