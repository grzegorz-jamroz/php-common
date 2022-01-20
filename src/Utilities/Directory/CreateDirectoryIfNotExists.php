<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\Directory;

use Ifrost\Common\Executable;

class CreateDirectoryIfNotExists implements Executable
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function execute(): void
    {
        if (file_exists($this->path)) {
            return;
        }

        if (!mkdir($this->path, 0777, true)) {
            throw new \RuntimeException(sprintf('Unable to create directory "%s".', $this->path));
        }
    }
}
