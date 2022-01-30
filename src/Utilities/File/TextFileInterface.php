<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

use Ifrost\Common\Utilities\File\Exception\FileAlreadyExists;

interface TextFileInterface extends FileInterface
{
    /**
     * @throws FileAlreadyExists when file already exists
     */
    public function create(string $content = ''): void;

    /**
     * The method will add new content to the end of the file.
     * The method will create a new file if it does not exist with given content.
     * The method will create the missing directories if necessary.
     */
    public function write(string $content): void;
    public function overwrite(string $content): void;
    public function read(): string;
}
