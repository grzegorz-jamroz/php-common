<?php

declare(strict_types=1);

namespace Tests\Utilities\File;

use Ifrost\Common\Utilities\File\GetDirectoryPathFromFilename;
use PHPUnit\Framework\TestCase;

class GetDirectoryPathFromFilenameTest extends TestCase
{
    public function testShouldReturnSlashWhenFilenameIndicateToRoot()
    {
        // Given
        $filename = '/test.txt';

        // When
        $actual = (new GetDirectoryPathFromFilename($filename))->acquire();

        // Then
        $this->assertEquals('/', $actual);
    }

    public function testShouldReturnDirectoryPathWithoutLastSlashForGivenFilename()
    {
        // Given
        $filename = '/data/test/text.txt';

        // When
        $actual = (new GetDirectoryPathFromFilename($filename))->acquire();

        // Then
        $this->assertEquals('/data/test', $actual);
    }

    public function testShouldReturnDirectoryPathWhenFilenameContainsBackslash()
    {
        // Given
        $filename = '\var\www/data/test/text.txt';

        // When
        $actual = (new GetDirectoryPathFromFilename($filename))->acquire();

        // Then
        $this->assertEquals('\var\www/data/test', $actual);
    }

    public function testShouldThrowInvalidArgumentExceptionWhenFilenameDoesNotContainAnyTrailingSlashes()
    {
        // Expect
        $this->expectException(\InvalidArgumentException::class);

        // Given
        $filename = 'text.txt';

        // When & Then
        (new GetDirectoryPathFromFilename($filename))->acquire();
    }
}
