<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

use Ifrost\Common\Interface\Executable;
use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;

class CreateFileIfNotExists implements Executable
{
    /**
     * @var string fully path to file
     */
    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function execute(): void
    {
        if (is_file($this->filename)) {
            return;
        }

        $directoryPath = (new GetDirectoryPathFromFilename($this->filename))->acquire();
        (new CreateDirectoryIfNotExists($directoryPath))->execute();

        try {
            file_put_contents($this->filename, 'something') ?: throw new \RuntimeException();
        } catch (\Exception) {
            throw new \RuntimeException(sprintf('Unable to create file "%s".', $this->filename));
        }
    }
}
