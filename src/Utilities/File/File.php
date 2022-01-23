<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

class File implements FileInterface
{
    /**
     * @param string $filename fully path to file
     */
    public function __construct(private string $filename)
    {
    }

    public function create(): void
    {
        (new CreateFileIfNotExists($this->filename))->execute();
    }

    public function write(string $content): void
    {
        (new WriteFile($this->filename, $content))->execute();
    }

    public function overwrite(string $content): void
    {
        (new OverwriteFile($this->filename, $content))->execute();
    }

    public function delete(): void
    {
        (new DeleteFile($this->filename))->execute();
    }

    public function read(): string
    {
        return (new ReadFile($this->filename))->acquire();
    }

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
        return (new GetDirectoryPath($this->filename))->acquire();
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
