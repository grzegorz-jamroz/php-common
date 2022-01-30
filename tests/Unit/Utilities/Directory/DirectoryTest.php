<?php

declare(strict_types=1);

namespace Tests\Unit\Utilities\Directory;

use Ifrost\Common\Utilities\Directory\Directory;
use PHPUnit\Framework\TestCase;

class DirectoryTest extends TestCase
{
    public function testShouldReturnDirectoryPath()
    {
        // Given
        $directoryPath1 = '/';
        $directoryPath2 = '/a';
        $directoryPath3 = '/data/test/test2';
        $directoryPath4 = '\var\www/data/test/test2';

        // When
        $actual1 = (new Directory($directoryPath1))->getPath();
        $actual2 = (new Directory($directoryPath2))->getPath();
        $actual3 = (new Directory($directoryPath3))->getPath();
        $actual4 = (new Directory($directoryPath4))->getPath();

        // Then
        $this->assertEquals('/', $actual1);
        $this->assertEquals('/a', $actual2);
        $this->assertEquals('/data/test/test2', $actual3);
        $this->assertEquals('\var\www/data/test/test2', $actual4);
    }
}
