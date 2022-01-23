<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

class TextFile extends File implements TextFileInterface
{
    /**
     * Creates a new file if it does not exist.
     * The method will create the missing directories if necessary.
     *
     * @throws \Exception when file already exists
     */
    public function create(string $content = ''): void
    {
        (new CreateFileIfNotExists($this->filename, $content))->execute();
    }

    /**
     * The method will add new content to the end of the file.
     * The method will create a new file if it does not exist with given content.
     * The method will create the missing directories if necessary.
     */
    public function write(string $content): void
    {
        (new WriteFile($this->filename, $content))->execute();
    }

    /**
     * The method will replace all file content with new content.
     * The method will create a new file if it does not exist with given content.
     * The method will create the missing directories if necessary.
     */
    public function overwrite(string $content): void
    {
        (new OverwriteFile($this->filename, $content))->execute();
    }

    /**
     * The method will return the contents of the file if it exists.
     */
    public function read(): string
    {
        return (new ReadFile($this->filename))->acquire();
    }
}
