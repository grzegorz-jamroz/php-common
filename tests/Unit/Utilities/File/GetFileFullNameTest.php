<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\File\File;
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
        $actual1 = (new File($filename1))->getFullName();
        $actual2 = (new File($filename2))->getFullName();
        $actual3 = (new File($filename3))->getFullName();
        $actual4 = (new File($filename4))->getFullName();

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
        (new File($filename))->getFullName();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameDoesNotContainAnyTrailingSlashes()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $filename = 'text.txt';

        // When & Then
        (new File($filename))->getFullName();
    }
}
