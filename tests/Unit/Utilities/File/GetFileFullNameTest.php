<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\File\GetFileFullName;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class GetFileFullNameTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldReturnFileNameForGivenFilename()
    {
        // Given
        $filename1 = '/one.txt';
        $filename2 = '/a';
        $filename3 = '/data/test/three.txt';
        $filename4 = '\var\www/data/test/four.txt';

        // When
        $actual1 = (new GetFileFullName($filename1))->acquire();
        $actual2 = (new GetFileFullName($filename2))->acquire();
        $actual3 = (new GetFileFullName($filename3))->acquire();
        $actual4 = (new GetFileFullName($filename4))->acquire();

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
        (new GetFileFullName($filename))->acquire();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameDoesNotContainAnyTrailingSlashes()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $filename = 'text.txt';

        // When & Then
        (new GetFileFullName($filename))->acquire();
    }
}
