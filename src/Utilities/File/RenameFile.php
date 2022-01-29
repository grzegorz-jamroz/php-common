<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

use Ifrost\Common\Interfaces\Executable;
use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;
use Ifrost\Common\Utilities\File\Exception\FileAlreadyExists;
use Ifrost\Common\Utilities\File\Exception\FileNotExist;

class RenameFile implements Executable
{
    /**
     * @param string $oldFilename fully path to file
     * @param string $newFilename fully path to file
     */
    public function __construct(
        private string $oldFilename,
        private string $newFilename,
    ) {
    }

    /**
     * Renames a file if it exists.
     * The new filename cannot exist.
     * The method will create the missing directories if necessary.
     */
    public function execute(): void
    {
        if (!is_file($this->oldFilename)) {
            throw new FileNotExist(sprintf('Unable rename file "%s". Old file does not exist.', $this->oldFilename));
        }

        if (is_file($this->newFilename)) {
            throw new FileAlreadyExists(sprintf('Unable rename file "%s". New file already exists.', $this->newFilename));
        }

        $directoryPath = (new GetDirectoryPath($this->newFilename))->acquire();
        (new CreateDirectoryIfNotExists($directoryPath))->execute();

        try {
            rename($this->oldFilename, $this->newFilename) ?: throw new \RuntimeException();
        } catch (\Exception) {
            throw new \RuntimeException(sprintf('Unable rename file "%s". ', $this->oldFilename));
        }
    }
}
