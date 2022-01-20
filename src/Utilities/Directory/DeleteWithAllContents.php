<?php
declare(strict_types=1);

namespace Ifrost\Common\Utilities\Directory;

use Ifrost\Common\HandleInterface;

class DeleteWithAllContents implements HandleInterface
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function handle(): void
    {
        $this->delete($this->path);
    }

    private function delete(string $dirPath): void
    {
        if (!is_dir($dirPath)) {
            throw new \InvalidArgumentException(sprintf("%s is not directory.", $dirPath));
        }

        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }

        $files = glob($dirPath . '*', GLOB_MARK);

        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->delete($file);
            } else {
                try {
                    unlink($file);
                } catch (\Exception $e) {
                    throw new \Exception(sprintf('Unable remove file "%s".', $file));
                }
            }
        }

        if (!rmdir($dirPath)) {
            throw new \Exception(sprintf('Unable remove directory "%s".', $dirPath));
        }
    }
}
