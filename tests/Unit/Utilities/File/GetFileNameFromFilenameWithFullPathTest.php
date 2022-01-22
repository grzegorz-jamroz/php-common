<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\File\GetFileNameFromFilenameWithFullPath;
use PHPUnit\Framework\TestCase;

class GetFileNameFromFilenameWithFullPathTest extends TestCase
{
    protected function setUp(): void
    {
        (new CreateDirectoryIfNotExists(DATA_DIRECTORY))->execute();
    }

    public function testShouldReturnFileNameForGivenFilename()
    {
        // Given
        $filename1 = '/one.txt';
        $filename2 = '/a';
        $filename3 = '/data/test/three.txt';
        $filename4 = '\var\www/data/test/four.txt';

        // When
        $actual1 = (new GetFileNameFromFilenameWithFullPath($filename1))->acquire();
        $actual2 = (new GetFileNameFromFilenameWithFullPath($filename2))->acquire();
        $actual3 = (new GetFileNameFromFilenameWithFullPath($filename3))->acquire();
        $actual4 = (new GetFileNameFromFilenameWithFullPath($filename4))->acquire();

        // Then
        $this->assertEquals('one.txt', $actual1);
        $this->assertEquals('a', $actual2);
        $this->assertEquals('three.txt', $actual3);
        $this->assertEquals('four.txt', $actual4);
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameLengthIsLowerThanTwoCharacters()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $filename = '/';

        // When & Then
        (new GetFileNameFromFilenameWithFullPath($filename))->acquire();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameDoesNotContainAnyTrailingSlashes()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $filename = 'text.txt';

        // When & Then
        (new GetFileNameFromFilenameWithFullPath($filename))->acquire();
    }
}
