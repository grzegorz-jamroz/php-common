<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\Directory;

use Ifrost\Common\Interfaces\Executable;
use Ifrost\Common\Utilities\Directory\Exception\DirectoryAlreadyExists;
use Ifrost\Common\Utilities\Directory\Exception\DirectoryNotExist;

class RenameDirectory implements Executable
{
    /**
     * @param string $oldDirectory fully path to directory
     * @param string $newDirectory fully path to directory
     */
    public function __construct(
        private string $oldDirectory,
        private string $newDirectory,
    ) {
    }

    /**
     * Renames a directory if it exists.
     * The new directory cannot exist.
     * The method will create the missing directories if necessary.
     */
    public function execute(): void
    {
        if (!is_dir($this->oldDirectory)) {
            throw new DirectoryNotExist(sprintf('Unable rename directory "%s". Old directory does not exist.', $this->oldDirectory));
        }

        if (is_dir($this->newDirectory)) {
            throw new DirectoryAlreadyExists(sprintf('Unable rename directory "%s". New directory already exists.', $this->newDirectory));
        }

        $newDirectoryPath = (new GetDirectoryParentPath($this->newDirectory))->acquire();

        try {
            (new CreateDirectoryIfNotExists($newDirectoryPath))->execute();
        } catch (DirectoryAlreadyExists) {
        }

        try {
            rename($this->oldDirectory, $this->newDirectory) ?: throw new \RuntimeException();
        } catch (\Exception) {
            throw new \RuntimeException(sprintf('Unable rename directory "%s". ', $this->oldDirectory));
        }
    }
}
