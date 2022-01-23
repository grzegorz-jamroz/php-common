<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

interface TextFileInterface extends FileInterface
{
    public function create(string $content = ''): void;
    public function write(string $content): void;
    public function overwrite(string $content): void;
    public function read(): string;
}
