<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

use Ifrost\Common\Interfaces\Executable;
use Ifrost\Common\Utilities\Directory\CreateDirectoryIfNotExists;

class CreateFileIfNotExists implements Executable
{
    /**
     * @param string $filename fully path to file
     */
    public function __construct(
        private string $filename,
        private string $content = '',
    ) {
    }

    /**
     * Creates a new file if it does not exist.
     * The method will create the missing directories if necessary.
     *
     * @throws \Exception when file already exists
     */
    public function execute(): void
    {
        if (is_file($this->filename)) {
            throw new \Exception(sprintf('Unable to create file "%s". File already exists.', $this->filename));
        }

        $directoryPath = (new GetDirectoryPath($this->filename))->acquire();
        (new CreateDirectoryIfNotExists($directoryPath))->execute();

        try {
            $file = fopen($this->filename, 'w+') ?: throw new \RuntimeException();
        } catch (\Exception) {
            throw new \RuntimeException(sprintf('Unable to create file "%s".', $this->filename));
        }

        fwrite($file, $this->content);
        fclose($file);
    }
}
