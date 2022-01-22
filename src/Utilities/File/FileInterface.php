<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

interface FileInterface
{
    public function create(): void;
    public function edit(): void;
    public function delete(): void;
    public function rename(): void;
    public function getDirectoryPath(): string;
    public function getFilename(): string;
    public function getFullName(): string;
    public function getName(): string;
    public function getExtension(): string;
}
