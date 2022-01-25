<?php
declare(strict_types=1);

namespace Tests\Traits;

use PHPUnit\Framework\TestCase;

trait PhpOsCheck
{
    protected function endTestIfWindowsOs(TestCase $testCase): void
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $testCase->expectException(\RuntimeException::class);
            throw new \RuntimeException('The operating system PHP was built for Windows.');
        }
    }
}
