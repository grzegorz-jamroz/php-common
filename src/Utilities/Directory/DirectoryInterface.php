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
    public function rename(string $newDirectoryPath): void;
    public function getPath(): string;
    public function getParentPath(): string;

    /**
     * @param array<string, mixed> $options
     * @description options:
     * extension => string | default: empty string
     * recursive => bool | default: false
     * order => string (asc or desc) | default: asc
     *
     * @return array<int, string>
     */
    public function getFiles(array $options = []): array;

    /**
     * @param array<string, mixed> $options
     * @description options:
     * extension => string | default: empty string
     * recursive => bool | default: false
     * order => string (asc or desc) | default: asc
     */
    public function countFiles(array $options = []): int;

    /**
     * @param array<string, mixed> $options
     * @description options:
     * extension => string | default: empty string
     * recursive => bool | default: false
     * order => string (asc or desc) | default: asc
     */
    public function countDirectories(array $options = []): int;

    /**
     * @param array<string, mixed> $options
     * @description options:
     * extension => string | default: empty string
     * recursive => bool | default: false
     * order => string (asc or desc) | default: asc
     */
    public function countFilesAndDirectories(array $options = []): int;
}
