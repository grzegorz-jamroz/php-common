<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\Directory;

use Ifrost\Common\Utilities\Directory\Exception\DirectoryAlreadyExists;

interface DirectoryInterface
{
    /**
     * Creates a new directory if it does not exist.
     * The method will create the missing directories if necessary.
     *
     * @throws DirectoryAlreadyExists when directory already exists
     */
    public function create(
        int $permissions = 0777,
        bool $recursive = true
    ): void;
    public function delete(): void;
    public function copy(string $newDirectoryPath): void;
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
     * recursive => bool | default: false
     * order => string (asc or desc) | default: asc
     *
     * @return array<int, string>
     */
    public function getDirectories(array $options = []): array;

    /**
     * @param array<string, mixed> $options
     * @description options:
     * recursive => bool | default: false
     * order => string (asc or desc) | default: asc
     */
    public function countFiles(array $options = []): int;

    /**
     * @param array<string, mixed> $options
     * @description options:
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
