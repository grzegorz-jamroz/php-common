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

    public function overwrite(): void
    {
        // TODO: Implement edit() method.
    }

    public function delete(): void
    {
        (new DeleteFile($this->filename))->execute();
    }

    public function rename(): void
    {
        // TODO: Implement rename() method.
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
        // TODO: Implement getExtension() method.
    }
}
