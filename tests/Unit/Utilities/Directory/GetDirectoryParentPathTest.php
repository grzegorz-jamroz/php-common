<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\Directory;

use Ifrost\Common\Utilities\Directory\GetDirectoryParentPath;
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
        $actual1 = (new GetDirectoryParentPath($directoryPath1))->acquire();
        $actual2 = (new GetDirectoryParentPath($directoryPath2))->acquire();
        $actual3 = (new GetDirectoryParentPath($directoryPath3))->acquire();
        $actual4 = (new GetDirectoryParentPath($directoryPath4))->acquire();

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
        (new GetDirectoryParentPath($directoryPath))->acquire();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenDirectoryPathDoesNotContainAnyTrialingSlash()
    {
        // Expect & Given
        $directoryPath = 'somedirectory';
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Directory path has to contain at least one Trailing Slash "/" or "\" character. Invalid directory path "%s".', $directoryPath));

        // When & Then
        (new GetDirectoryParentPath($directoryPath))->acquire();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenDirectoryPathDoesNotContainAnyDirectoryNameAfterLastTrialingSlash()
    {
        // Expect & Given
        $directoryPath = '\data/test/';
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Directory path has to contain string after last Trailing Slash "/" or "\" character. Invalid directory path "%s".', $directoryPath));

        // When & Then
        (new GetDirectoryParentPath($directoryPath))->acquire();
    }
}
