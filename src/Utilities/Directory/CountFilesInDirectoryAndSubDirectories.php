<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\Directory;

use Ifrost\Common\Interfaces\Acquirable;

class CountFilesInDirectoryAndSubDirectories implements Acquirable
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function acquire(): int
    {
        return $this->count($this->path, 0);
    }

    private function count(string $dirPath, $counter): int
    {
        if (!is_dir($dirPath)) {
            throw new \InvalidArgumentException(sprintf('%s is not directory.', $dirPath));
        }

        if (substr($dirPath, strlen($dirPath) - 1, 1) !== '/') {
            $dirPath .= '/';
        }

        $files = glob($dirPath . '*', GLOB_MARK) ?: [];

        foreach ($files as $file) {
            if (is_dir($file)) {
                $counter = $this->count($file, $counter);

                continue;
            }

            ++$counter;
        }

        return $counter;
    }
}
