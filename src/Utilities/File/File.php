<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

class File implements FileInterface
{
    /**
     * @var string fully path to file
     */
    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function create(): void
    {
        (new CreateFileIfNotExists($this->filename))->execute();
    }

    public function overwrite(string $content): void
    {
        (new OverwriteFile($this->filename, $content))->execute();
    }

    public function delete(): void
    {
        (new DeleteFile($this->filename))->execute();
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
}
