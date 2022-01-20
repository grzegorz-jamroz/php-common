<?php

declare(strict_types=1);

namespace Tests\Utilities\File;

use Ifrost\Common\Utilities\File\CreateFileIfNotExists;
use PHPUnit\Framework\TestCase;

class CreateFileIfNotExistsTest extends TestCase
{
    public function testShouldCreateFileInNotExistedDirectory()
    {
        // Given
        $directoryPath = sprintf('%s/test/test.txt', DATA_DIRECTORY);

        // When
        (new CreateFileIfNotExists($directoryPath))->execute();

        // Then
        $this->assertEquals(true, file_exists($directoryPath));
        $this->assertEquals(true, is_file($directoryPath));
    }
}
