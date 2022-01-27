<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\Directory;

interface DirectoryInterface
{
    public function create(
        int $permissions = 0777,
        bool $recursive = true
    ): void;
    public function delete(): void;
    public function rename(string $newFilename): void;
    public function getPath(): string;

    /**
     * @param array<string, mixed> $options
     * @description options:
     * extension => string
     * recursive => bool
     * order => string (asc or desc)
     *
     * @return array<int, string>
     */
    public function getFiles(array $options = []): array;

    /**
     * @param array<string, mixed> $options
     * @description options:
     * extension => string
     * recursive => bool
     * order => string (asc or desc)
     */
    public function countFiles(array $options = []): int;

    /**
     * @param array<string, mixed> $options
     * @description options:
     * extension => string
     * recursive => bool
     * order => string (asc or desc)
     */
    public function countDirectories(array $options = []): int;

    /**
     * @param array<string, mixed> $options
     * @description options:
     * extension => string
     * recursive => bool
     * order => string (asc or desc)
     */
    public function countFilesAndDirectories(array $options = []): int;
}
