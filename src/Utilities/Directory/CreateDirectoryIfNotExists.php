<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\Directory;

use Ifrost\Common\Interfaces\Executable;
use Ifrost\Common\Utilities\Directory\Exception\DirectoryAlreadyExists;

class CreateDirectoryIfNotExists implements Executable
{
    public function __construct(
        private string $path,
        private int $permissions = 0777,
        private bool $recursive = true,
    ) {
    }

    /**
     * Creates a new directory if it does not exist.
     * The method will create the missing directories if necessary.
     *
     * @throws DirectoryAlreadyExists when directory already exists
     */
    public function execute(): void
    {
        if (file_exists($this->path)) {
            throw new DirectoryAlreadyExists(sprintf('Unable to create directory "%s". Directory already exists.', $this->path));
        }

        try {
            mkdir($this->path, $this->permissions, $this->recursive) ?: throw new \RuntimeException();
        } catch (\Exception) {
            throw new \RuntimeException(sprintf('Unable to create directory "%s".', $this->path));
        }
    }
}
