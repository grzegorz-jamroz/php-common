<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\Directory;

use Ifrost\Common\Utilities\Directory\CountFilesInDirectory;
use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\Directory\DeleteDirectoryWithAllContent;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class DeleteDirectoryWithAllContentTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        (new CreateDirectoryIfNotExists(DATA_DIRECTORY))->execute();
    }

    public function testShouldLetNothingHappenWhenDirectoryNotExists()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/delete-directory/sample_%s', DATA_DIRECTORY, time());
        $this->assertDirectoryDoesNotExist($directoryPath);

        // When
        try {
            (new DeleteDirectoryWithAllContent($directoryPath))->execute();
        } catch (\Exception) {
            $this->assertEquals(1, 1);
        }

        // Then
        $this->assertEquals(1, $this->getCount());
    }

    public function testShouldRemoveEmptyDirectory()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/delete-directory/empty-directory', DATA_DIRECTORY);
        (new CreateDirectoryIfNotExists($directoryPath))->execute();

        // When
        (new DeleteDirectoryWithAllContent($directoryPath))->execute();

        // Then
        $this->assertEquals(false, file_exists($directoryPath));
    }

    public function testShouldRemoveDirectoryWithOneFileInside()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/delete-directory/one-file-inside', DATA_DIRECTORY);
        $filename = sprintf('%s/something.txt', $directoryPath);
        $this->createFileIfNotExists($filename);
        $files = glob($directoryPath . '/*', GLOB_MARK);
        $this->assertEquals(1, count($files));

        // When
        (new DeleteDirectoryWithAllContent($directoryPath))->execute();

        // Then
        $this->assertDirectoryDoesNotExist($directoryPath);
        $this->assertFileDoesNotExist($filename);
    }

    public function testShouldRemoveDirectoryWithTwoFilesInside()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/delete-directory/two-files-inside', DATA_DIRECTORY);
        $filename1 = sprintf('%s/something1.txt', $directoryPath);
        $filename2 = sprintf('%s/something2.txt', $directoryPath);
        $this->createFileIfNotExists($filename1);
        $this->createFileIfNotExists($filename2);
        $files = glob($directoryPath . '/*', GLOB_MARK);
        $this->assertEquals(2, count($files));

        // When
        (new DeleteDirectoryWithAllContent($directoryPath))->execute();

        // Then
        $this->assertDirectoryDoesNotExist($directoryPath);
        $this->assertFileDoesNotExist($filename1);
        $this->assertFileDoesNotExist($filename2);
    }

    public function testShouldRemoveDirectoryWithSubDirectoriesAndAllContent()
    {
        // Expect & Given
        $mainDirectoryPath = sprintf('%s/directory/delete-directory/dir-to-remove', DATA_DIRECTORY);
        $directoryPath2 = sprintf('%s/nested1/nested', $mainDirectoryPath);
        $directoryPath3 = sprintf('%s/nested2', $mainDirectoryPath);
        $filenames = [
            sprintf('%s/something1.txt', $mainDirectoryPath),
            sprintf('%s/something2.txt', $mainDirectoryPath),
            sprintf('%s/something1.txt', $directoryPath2),
            sprintf('%s/something2.txt', $directoryPath2),
            sprintf('%s/something1.txt', $directoryPath3),
            sprintf('%s/something2.txt', $directoryPath3),
        ];

        foreach ($filenames as $filename) {
            $this->createFileIfNotExists($filename);
        }

        $this->assertEquals(6, (new CountFilesInDirectory($mainDirectoryPath, ['recursive' => true]))->acquire());

        // When
        (new DeleteDirectoryWithAllContent($mainDirectoryPath))->execute();

        // Then
        foreach ($filenames as $filename) {
            $this->assertFileDoesNotExist($filename);
        }

        $this->assertDirectoryDoesNotExist($mainDirectoryPath);
    }

    public function testShouldThrowInvalidArgumentExceptionWhenPathIsFile()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $directoryPath = sprintf('%s/directory/delete-directory/some-dir', DATA_DIRECTORY);
        $filename = sprintf('%s/test.txt', $directoryPath);
        $this->createFileIfNotExists($filename);
        $this->assertFileExists($filename);

        // When
        (new DeleteDirectoryWithAllContent($filename))->execute();
    }

    /**
     * it probably only works on ext2/ext3/ext4 filesystems.
     */
    public function testShouldThrowRuntimeExceptionWhenUnableToDeleteDirectory()
    {
        $this->endTestIfWindowsOs($this);
        $this->endTestIfEnvMissing($this, ['SUDOER_PASSWORD']);

        // Expect & Given
        $directoryPath = sprintf('%s/immutable_dir', TESTS_DATA_DIRECTORY);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable remove directory "%s".', $directoryPath));
        $this->createImmutableDirectory($directoryPath);
        $this->assertDirectoryExists($directoryPath);

        // When & Then
        (new DeleteDirectoryWithAllContent($directoryPath))->execute();
    }
}
