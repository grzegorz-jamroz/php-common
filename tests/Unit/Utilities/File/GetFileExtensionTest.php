<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\File\File;
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
        $actual1 = (new File($filename1))->getExtension();
        $actual2 = (new File($filename2))->getExtension();
        $actual3 = (new File($filename3))->getExtension();
        $actual4 = (new File($filename4))->getExtension();

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
        (new File($filename))->getExtension();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameDoesNotContainAnyTrailingSlashes()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $filename = 'text.txt';

        // When & Then
        (new File($filename))->getExtension();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameDoesNotContainAnyFileName()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $filename = '\data/test/';

        // When & Then
        (new File($filename))->getExtension();
    }
}
