<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

use Ifrost\Common\Interfaces\Executable;

class OverwriteFile implements Executable
{
    /**
     * @param string $filename fully path to file
     */
    public function __construct(
        private string $filename,
        private string $content,
    ) {
    }

    public function execute(): void
    {
        if (!is_file($this->filename)) {
            (new CreateFileIfNotExists($this->filename))->execute();
        }

        try {
            $file = fopen($this->filename, 'w+') ?: throw new \RuntimeException();
        } catch (\Exception) {
            throw new \RuntimeException(sprintf('Unable to overwrite file "%s".', $this->filename));
        }

        fwrite($file, $this->content);
        fclose($file);
    }
}
