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
     */
    public function getFiles(array $options = []): int;
    public function getNumberOfFiles(bool $includeSubDirectories = false): int;
    public function getNumberOfDirectories(bool $includeSubDirectories = false): int;
    public function getNumberOfFilesAndDirectories(bool $includeSubDirectories = false): int;
}
