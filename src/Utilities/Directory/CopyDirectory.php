<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\Directory;

use Ifrost\Common\Interfaces\Executable;
use Ifrost\Common\Utilities\Directory\Exception\DirectoryAlreadyExists;
use Ifrost\Common\Utilities\Directory\Exception\DirectoryNotExist;
use Ifrost\Common\Utilities\File\CopyFile;

class CopyDirectory implements Executable
{
    /**
     * @param string $oldDirectoryPath fully path to directory
     * @param string $newDirectoryPath fully path to directory
     */
    public function __construct(
        private string $oldDirectoryPath,
        private string $newDirectoryPath,
    ) {
    }

    public function execute(): void
    {
        if (!is_dir($this->oldDirectoryPath)) {
            throw new DirectoryNotExist(sprintf('Unable copy directory "%s". Old directory does not exist.', $this->oldDirectoryPath));
        }

        try {
            (new CreateDirectoryIfNotExists($this->newDirectoryPath))->execute();
        } catch (DirectoryAlreadyExists) {
            throw new DirectoryAlreadyExists(sprintf('Unable copy directory "%s". New directory already exists.', $this->newDirectoryPath));
        }

        try {
            $this->copyContent();
        } catch (\Exception) {
            throw new \RuntimeException(sprintf('Unable copy directory "%s".', $this->oldDirectoryPath));
        }
    }

    private function copyContent(): void
    {
        $paths = (new GetFilesAndSubDirectoriesFromDirectory($this->oldDirectoryPath, ['recursive' => true]))->acquire();

        foreach ($paths as $path) {
            $newPath = str_replace($this->oldDirectoryPath, $this->newDirectoryPath, $path);

            if (is_dir($path)) {
                (new CreateDirectoryIfNotExists($newPath))->execute();
            }

            if (is_file($path)) {
                (new CopyFile($path, $newPath))->execute();
            }
        }
    }
}
