<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\Directory;

use Ifrost\Common\Utilities\Directory\Directory;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class GetDirectoryParentPathTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldReturnParentDirectoryPathForGivenDriectoryPath()
    {
        // Given
        $directoryPath1 = '/';
        $directoryPath2 = '/a';
        $directoryPath3 = '/data/test/test2';
        $directoryPath4 = '\var\www/data/test/test2';

        // When
        $actual1 = (new Directory($directoryPath1))->getParentPath();
        $actual2 = (new Directory($directoryPath2))->getParentPath();
        $actual3 = (new Directory($directoryPath3))->getParentPath();
        $actual4 = (new Directory($directoryPath4))->getParentPath();

        // Then
        $this->assertEquals('/', $actual1);
        $this->assertEquals('/', $actual2);
        $this->assertEquals('/data/test', $actual3);
        $this->assertEquals('\var\www/data/test', $actual4);
    }

    public function testShouldThrowInvalidArgumentExceptionWhenDirectoryPathLengthIsLowerThanTwoCharactersAndDirectoryPathIsNotSlash()
    {
        // Expect & Given
        $directoryPath = 'a';
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Directory path has to contain at least one Trailing Slash "/" or "\" character. Invalid directory path "%s".', $directoryPath));

        // When & Then
        (new Directory($directoryPath))->getParentPath();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenDirectoryPathDoesNotContainAnyTrialingSlash()
    {
        // Expect & Given
        $directoryPath = 'somedirectory';
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Directory path has to contain at least one Trailing Slash "/" or "\" character. Invalid directory path "%s".', $directoryPath));

        // When & Then
        (new Directory($directoryPath))->getParentPath();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenDirectoryPathDoesNotContainAnyDirectoryNameAfterLastTrialingSlash()
    {
        // Expect & Given
        $directoryPath = '\data/test/';
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Directory path has to contain string after last Trailing Slash "/" or "\" character. Invalid directory path "%s".', $directoryPath));

        // When & Then
        (new Directory($directoryPath))->getParentPath();
    }
}
