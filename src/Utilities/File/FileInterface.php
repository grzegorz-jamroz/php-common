<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

interface FileInterface
{
    public function create(): void;
    public function write(string $content): void;
    public function overwrite(string $content): void;
    public function delete(): void;
    public function read(): string;
    public function rename(string $newFilename): void;
    public function getDirectoryPath(): string;
    public function getFilename(): string;
    public function getFullName(): string;
    public function getName(): string;
    public function getExtension(): string;
}
