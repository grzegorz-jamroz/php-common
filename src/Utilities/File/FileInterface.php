<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

interface FileInterface
{
    public function create(): void;
    public function overwrite(): void;
    public function delete(): void;
    public function rename(string $newFilename): void;
    public function getDirectoryPath(): string;
    public function getFilename(): string;
    public function getFullName(): string;
    public function getName(): string;
    public function getExtension(): string;
}
