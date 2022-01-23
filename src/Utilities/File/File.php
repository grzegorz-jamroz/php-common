<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

class File implements FileInterface
{
    /**
     * @param string $filename fully path to file
     */
    public function __construct(protected string $filename)
    {
    }

    /**
     * Creates a new file if it does not exist.
     * The method will create the missing directories if necessary.
     */
    public function create(): void
    {
        (new CreateFileIfNotExists($this->filename))->execute();
    }

    public function delete(): void
    {
        (new DeleteFile($this->filename))->execute();
    }

    /**
     * Renames a file if it exists.
     * The new filename cannot exist.
     * The method will create the missing directories if necessary.
     */
    public function rename(string $newFilename): void
    {
        (new RenameFile($this->filename, $newFilename))->execute();
        $this->filename = $newFilename;
    }

    public function getDirectoryPath(): string
    {
        return (new GetDirectoryPath($this->filename))->acquire();
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getFullName(): string
    {
        return (new GetFileFullName($this->filename))->acquire();
    }

    public function getName(): string
    {
        return (new GetFileName($this->filename))->acquire();
    }

    public function getExtension(): string
    {
        return (new GetFileExtension($this->filename))->acquire();
    }

    public function getNumberOfLines(): int
    {
        return (new GetFileNumberOfLines($this->filename))->acquire();
    }

    public function getLine(int $lineNumber): string
    {
        return (new GetFileLine($this->filename, $lineNumber))->acquire();
    }
}
