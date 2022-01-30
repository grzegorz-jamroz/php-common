<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\File;

use Ifrost\Common\Utilities\File\File;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class GetDirectoryPathTest extends TestCase
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
        $filename3 = '/data/test/text.txt';
        $filename4 = '\var\www/data/test/text.txt';

        // When
        $actual1 = (new File($filename1))->getDirectoryPath();
        $actual2 = (new File($filename2))->getDirectoryPath();
        $actual3 = (new File($filename3))->getDirectoryPath();
        $actual4 = (new File($filename4))->getDirectoryPath();

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
        (new File($filename))->getDirectoryPath();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameDoesNotContainAnyTrailingSlashes()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $filename = 'text.txt';

        // When & Then
        (new File($filename))->getDirectoryPath();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameDoesNotContainAnyFileName()
    {
        // Expect & Given
        $filename = '\data/test/';
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Filename has to contain string after last Trailing Slash "/" character. Invalid filename "%s".', $filename));

        // When & Then
        (new File($filename))->getDirectoryPath();
    }
}
