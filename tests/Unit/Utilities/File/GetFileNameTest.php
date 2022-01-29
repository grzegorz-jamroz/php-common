<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\File\GetFileName;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class GetFileNameTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldReturnDirectoryPathForGivenFilename()
    {
        // Given
        $filename1 = '/test.txt';
        $filename2 = '/a';
        $filename3 = '/data/test/ab.txt';
        $filename4 = '\var\www/data/test/ab_c.txt';

        // When
        $actual1 = (new GetFileName($filename1))->acquire();
        $actual2 = (new GetFileName($filename2))->acquire();
        $actual3 = (new GetFileName($filename3))->acquire();
        $actual4 = (new GetFileName($filename4))->acquire();

        // Then
        $this->assertEquals('test', $actual1);
        $this->assertEquals('a', $actual2);
        $this->assertEquals('ab', $actual3);
        $this->assertEquals('ab_c', $actual4);
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameLengthIsLowerThanTwoCharacters()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $filename = '/';

        // When & Then
        (new GetFileName($filename))->acquire();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameDoesNotContainAnyTrailingSlashes()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $filename = 'text.txt';

        // When & Then
        (new GetFileName($filename))->acquire();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameDoesNotContainAnyFileName()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $filename = '\data/test/';

        // When & Then
        (new GetFileName($filename))->acquire();
    }
}
