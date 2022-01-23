<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\Directory;

class Directory implements DirectoryInterface
{
    /**
     * @param string $filename fully path to file
     */
    public function __construct(private string $path)
    {
    }

    public function create(
        int $permissions = 0777,
        bool $recursive = true
    ): void {
        (new CreateDirectoryIfNotExists($this->path, $permissions, $recursive))->execute();
    }

    public function delete(): void
    {
        (new DeleteDirectoryWithAllContent($this->path))->execute();
    }

    public function rename(string $newFilename): void
    {
        throw new \Exception('rename is not implemented yet.');
    }

    public function getPath(): string
    {
        throw new \Exception('getPath is not implemented yet.');
    }

    public function getNumberOfFiles(bool $includeSubDirectories = false): int
    {
        if ($includeSubDirectories) {
            return (new CountFilesInDirectoryAndSubDirectories($this->path))->acquire();
        }

        throw new \Exception('getNumberOfFiles without subDirectories is not implemented yet.');
    }

    public function getNumberOfDirectories(bool $includeSubDirectories = false): int
    {
        throw new \Exception('getNumberOfDirectories is not implemented yet.');
    }

    public function getNumberOfFilesAndDirectories(bool $includeSubDirectories = false): int
    {
        throw new \Exception('getNumberOfFilesAndDirectories is not implemented yet.');
    }
}