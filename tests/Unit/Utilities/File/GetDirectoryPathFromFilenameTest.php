<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\File\GetDirectoryPathFromFilename;
use PHPUnit\Framework\TestCase;

class GetDirectoryPathFromFilenameTest extends TestCase
{
    protected function setUp(): void
    {
        (new CreateDirectoryIfNotExists(DATA_DIRECTORY))->execute();
    }

    public function testShouldReturnDirectoryPathForGivenFilename()
    {
        // Given
        $filename1 = '/test.txt';
        $filename2 = '/a';
        $filename3 = '/data/test/text.txt';
        $filename4 = '\var\www/data/test/text.txt';

        // When
        $actual1 = (new GetDirectoryPathFromFilename($filename1))->acquire();
        $actual2 = (new GetDirectoryPathFromFilename($filename2))->acquire();
        $actual3 = (new GetDirectoryPathFromFilename($filename3))->acquire();
        $actual4 = (new GetDirectoryPathFromFilename($filename4))->acquire();

        // Then
        $this->assertEquals('/', $actual1);
        $this->assertEquals('/', $actual2);
        $this->assertEquals('/data/test', $actual3);
        $this->assertEquals('\var\www/data/test', $actual4);
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameLengthIsLowerThanTwoCharacters()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $filename = '/';

        // When & Then
        (new GetDirectoryPathFromFilename($filename))->acquire();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameDoesNotContainAnyTrailingSlashes()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $filename = 'text.txt';

        // When & Then
        (new GetDirectoryPathFromFilename($filename))->acquire();
    }
}
