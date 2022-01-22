<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\Directory;

use Ifrost\Common\Interface\Executable;

class CreateDirectoryIfNotExists implements Executable
{
    public function __construct(
        private string $path,
        private int $permissions = 0777,
        private bool $recursive = true,
    ) {
    }

    public function execute(): void
    {
        if (file_exists($this->path)) {
            return;
        }

        try {
            mkdir($this->path, $this->permissions, $this->recursive) ?: throw new \RuntimeException();
        } catch (\Exception) {
            throw new \RuntimeException(sprintf('Unable to create directory "%s".', $this->path));
        }
    }
}
