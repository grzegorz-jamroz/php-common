<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

class TextFile extends File implements TextFileInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(string $content = ''): void
    {
        (new CreateFileIfNotExists($this->filename, $content))->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function write(string $content): void
    {
        (new WriteFile($this->filename, $content))->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function overwrite(string $content): void
    {
        (new OverwriteFile($this->filename, $content))->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function read(): string
    {
        return (new ReadFile($this->filename))->acquire();
    }
}
