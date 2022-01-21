<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\Directory;

use Ifrost\Common\Executable;

class DeleteDirectoryWithAllContents implements Executable
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function execute(): void
    {
        $this->delete($this->path);
    }

    private function delete(string $dirPath): void
    {
        if (!is_dir($dirPath)) {
            throw new \InvalidArgumentException(sprintf('%s is not directory.', $dirPath));
        }

        if (substr($dirPath, strlen($dirPath) - 1, 1) !== '/') {
            $dirPath .= '/';
        }

        $files = glob($dirPath . '*', GLOB_MARK);

        if ($files === false) {
            throw new \RuntimeException(sprintf('Unable to find files inside "%s".', $dirPath));
        }

        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->delete($file);

                continue;
            }

            if (!unlink($file)) {
                throw new \RuntimeException(sprintf('Unable remove file "%s".', $dirPath));
            }
        }

        if (!rmdir($dirPath)) {
            throw new \RuntimeException(sprintf('Unable remove directory "%s".', $dirPath));
        }
    }
}
