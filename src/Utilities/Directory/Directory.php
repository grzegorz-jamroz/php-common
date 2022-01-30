<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\Directory;

class Directory implements DirectoryInterface
{
    /**
     * @param string $path fully path to directory
     */
    public function __construct(private string $path)
    {
    }

    /**
     * {@inheritDoc}
     */
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

    public function copy(string $newDirectoryPath): void
    {
        throw new \Exception('copy is not implemented yet.');
    }

    public function rename(string $newDirectoryPath): void
    {
        (new RenameDirectory($this->path, $newDirectoryPath))->execute();
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getParentPath(): string
    {
        return (new GetDirectoryParentPath($this->path))->acquire();
    }

    /**
     * {@inheritDoc}
     */
    public function getFiles(array $options = []): array
    {
        return (new GetFilesFromDirectory($this->path, $options))->acquire();
    }

    /**
     * {@inheritDoc}
     */
    public function getDirectories(array $options = []): array
    {
        return (new GetSubDirectoriesFromDirectory($this->path, $options))->acquire();
    }

    /**
     * {@inheritDoc}
     */
    public function getFilesAndDirectories(array $options = []): array
    {
        return (new GetFilesAndSubDirectoriesFromDirectory($this->path, $options))->acquire();
    }

    /**
     * {@inheritDoc}
     */
    public function countFiles(array $options = []): int
    {
        return (new CountFilesInDirectory($this->path, $options))->acquire();
    }

    /**
     * {@inheritDoc}
     */
    public function countDirectories(array $options = []): int
    {
        return (new CountSubDirectoriesInDirectory($this->path, $options))->acquire();
    }

    /**
     * {@inheritDoc}
     */
    public function countFilesAndDirectories(array $options = []): int
    {
        return (new CountFilesAndDirectories($this->path, $options))->acquire();
    }
}
