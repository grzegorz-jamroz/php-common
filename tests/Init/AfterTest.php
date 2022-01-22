<?php

declare(strict_types=1);

namespace Tests\Init;

use Ifrost\Common\Utilities\Directory\DeleteDirectoryWithAllContent;
use PHPUnit\Framework\TestCase;

class AfterTest extends TestCase
{
    public function testShouldCleanUpAfterAllTests()
    {
        // Given
        $directory = DATA_DIRECTORY;

        // When
        try {
            (new DeleteDirectoryWithAllContent(DATA_DIRECTORY))->execute();
        } catch (\Exception) {
        }

        // Then
        $this->assertDirectoryDoesNotExist($directory);
    }
}
