<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\File\GetFileExtension;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class GetFileExtensionTest extends TestCase
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
        $filename3 = '/data/test/ab.jpeg';
        $filename4 = '\var\www/data/test/ab_c.docx';

        // When
        $actual1 = (new GetFileExtension($filename1))->acquire();
        $actual2 = (new GetFileExtension($filename2))->acquire();
        $actual3 = (new GetFileExtension($filename3))->acquire();
        $actual4 = (new GetFileExtension($filename4))->acquire();

        // Then
        $this->assertEquals('txt', $actual1);
        $this->assertEquals('', $actual2);
        $this->assertEquals('jpeg', $actual3);
        $this->assertEquals('docx', $actual4);
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameLengthIsLowerThanTwoCharacters()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $filename = '/';

        // When & Then
        (new GetFileExtension($filename))->acquire();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameDoesNotContainAnyTrailingSlashes()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $filename = 'text.txt';

        // When & Then
        (new GetFileExtension($filename))->acquire();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameDoesNotContainAnyFileName()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $filename = '\data/test/';

        // When & Then
        (new GetFileExtension($filename))->acquire();
    }
}
