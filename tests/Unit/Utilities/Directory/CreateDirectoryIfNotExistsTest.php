<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\Directory;

use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class CreateDirectoryIfNotExistsTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        (new CreateDirectoryIfNotExists(DATA_DIRECTORY))->execute();
    }

    public function testShouldLetNothingHappenWhenDirectoryExists()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/create-directory/sample_%s', DATA_DIRECTORY, time());
        mkdir($directoryPath, 0777, true);
        $this->assertDirectoryExists($directoryPath);

        // When
        try {
            (new CreateDirectoryIfNotExists($directoryPath))->execute();
        } catch (\Exception) {
            $this->assertEquals(1, 1);
        }

        // Then
        $this->assertEquals(1, $this->getCount());
    }

    public function testShouldCreateDirectoryInNotExistedDirectory()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/create-directory/sample_%s/test', DATA_DIRECTORY, time());

        // When
        (new CreateDirectoryIfNotExists($directoryPath))->execute();

        // Then
        $this->assertDirectoryExists($directoryPath);
    }

    /**
     * it probably only works on ext2/ext3/ext4 filesystems.
     */
    public function testShouldThrowRuntimeExceptionWhenUnableToCreateDirectory()
    {
        $this->endTestIfWindowsOs($this);
        $this->endTestIfEnvMissing($this, ['PASSWORD']);

        // Expect & Given
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/Unable to create directory/');
        $directoryPath = sprintf('%s/immutable_dir', TESTS_DATA_DIRECTORY);
        $directoryPath2 = sprintf('%s/sample_%s', $directoryPath, time());
        $this->createImmutableDirectory($directoryPath);
        $this->assertDirectoryExists($directoryPath);
        $this->assertDirectoryDoesNotExist($directoryPath2);

        // When & Then
        (new CreateDirectoryIfNotExists($directoryPath2))->execute();
    }
}
