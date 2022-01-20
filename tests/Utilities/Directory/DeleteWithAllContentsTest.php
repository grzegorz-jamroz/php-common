<?php
declare(strict_types=1);

namespace Tests\Utilities\Directory;

use Ifrost\Common\Utilities\Directory\CreateIfNotExists;
use Ifrost\Common\Utilities\Directory\DeleteWithAllContents;
use PHPUnit\Framework\TestCase;

class DeleteWithAllContentsTest extends TestCase
{
    public function testShouldRemoveEmptyDirectory()
    {
        // Given
        $directoryPath = sprintf('%s/empty-directory', DATA_DIRECTORY);
        (new CreateIfNotExists($directoryPath))->handle();

        // When
        (new DeleteWithAllContents(DATA_DIRECTORY))->handle();

        // Then
        $this->assertEquals(false, file_exists($directoryPath));
    }
}
