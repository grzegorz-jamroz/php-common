<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\Directory;

use Ifrost\Common\Utilities\Directory\Directory;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class CountFilesAndDirectoriesTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldReturnIntegerFourWhenWhenDirectoryContainsFourSubDirectories()
    {
        // Expect & Given
        $mainDirectoryPath = sprintf('%s/directory/count-files-and-dirs/main', DATA_DIRECTORY);
        $directoryPath2 = sprintf('%s/nested1/nested', $mainDirectoryPath);
        $directoryPath3 = sprintf('%s/nested2', $mainDirectoryPath);
        $directoryPath4 = sprintf('%s/nested4', $mainDirectoryPath);
        $filenames = [
            sprintf('%s/something1.txt', $mainDirectoryPath),
            sprintf('%s/something2.txt', $mainDirectoryPath),
            sprintf('%s/something1.txt', $directoryPath2),
            sprintf('%s/something2.txt', $directoryPath2),
            sprintf('%s/something1.txt', $directoryPath3),
            sprintf('%s/something2.txt', $directoryPath3),
            sprintf('%s/something1.txt', $directoryPath4),
        ];

        foreach ($filenames as $filename) {
            $this->createFileIfNotExists($filename);
        }

        // When
        $actual = (new Directory($mainDirectoryPath))->countFilesAndDirectories(['recursive' => true]);

        $this->assertEquals(11, $actual);
    }
}
