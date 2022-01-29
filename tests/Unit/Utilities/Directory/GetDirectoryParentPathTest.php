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
        $filename1 = '/';
        $filename2 = '/a';
        $filename3 = '/data/test/test2';
        $filename4 = '\var\www/data/test/test2';

        // When
        $actual1 = (new GetDirectoryParentPath($filename1))->acquire();
        $actual2 = (new GetDirectoryParentPath($filename2))->acquire();
        $actual3 = (new GetDirectoryParentPath($filename3))->acquire();
        $actual4 = (new GetDirectoryParentPath($filename4))->acquire();

        // Then
        $this->assertEquals('/', $actual1);
        $this->assertEquals('/', $actual2);
        $this->assertEquals('/data/test', $actual3);
        $this->assertEquals('\var\www/data/test', $actual4);
    }

    public function testShouldThrowInvalidArgumentExceptionWhenDirectoryPathLengthIsLowerThanTwoCharactersAndDirectoryPathIsNotSlash()
    {
        // Expect & Given
        $filename = 'a';
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Directory path has to contain at least one Trailing Slash "/" or "\" character. Invalid directory path "%s".', $filename));

        // When & Then
        (new GetDirectoryParentPath($filename))->acquire();
    }

    public function testShouldThrowInvalidArgumentExceptionWhenDirectoryPathDoesNotContainAnyTrialingSlash()
    {
        // Expect & Given
        $filename = 'somedirectory';
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Directory path has to contain at least one Trailing Slash "/" or "\" character. Invalid directory path "%s".', $filename));

        // When & Then
        (new GetDirectoryParentPath($filename))->acquire();
    }
}
