<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\File\GetDirectoryPath;
use PHPUnit\Framework\TestCase;

class GetDirectoryPathTest extends TestCase
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
        $actual1 = (new GetDirectoryPath($filename1))->acquire();
        $actual2 = (new GetDirectoryPath($filename2))->acquire();
        $actual3 = (new GetDirectoryPath($filename3))->acquire();
        $actual4 = (new GetDirectoryPath($filename4))->acquire();

        // Then
        $this->assertEquals('/', $actual1);
        $this->assertEquals('/', $actual2);
        $this->assertEquals('/data/test', $actual3);
        $this->assertEquals('\var\www/data/test', $actual4);
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameLengthIsLowerThanTwoCharacters()
    {
        // Expect & Given
        $filename = '/';
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Filename has to contain string after last Trailing Slash "/" character. Invalid filename "%s".', $filename));

        // When & Then
        (new GetDirectoryPath($filename))->acquire();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameDoesNotContainAnyTrailingSlashes()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $filename = 'text.txt';

        // When & Then
        (new GetDirectoryPath($filename))->acquire();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameDoesNotContainAnyFileName()
    {
        // Expect & Given
        $filename = '\data/test/';
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Filename has to contain string after last Trailing Slash "/" character. Invalid filename "%s".', $filename));

        // When & Then
        (new GetDirectoryPath($filename))->acquire();
    }
}
