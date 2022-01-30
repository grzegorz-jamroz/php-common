<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\File\File;
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
        $actual1 = (new File($filename1))->getName();
        $actual2 = (new File($filename2))->getName();
        $actual3 = (new File($filename3))->getName();
        $actual4 = (new File($filename4))->getName();

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
        (new File($filename))->getName();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameDoesNotContainAnyTrailingSlashes()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $filename = 'text.txt';

        // When & Then
        (new File($filename))->getName();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameDoesNotContainAnyFileName()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $filename = '\data/test/';

        // When & Then
        (new File($filename))->getName();
    }
}
