<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

use Ifrost\Common\Interfaces\Executable;

class WriteFile implements Executable
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
            $file = fopen($this->filename, 'a+') ?: throw new \RuntimeException();
        } catch (\Exception) {
            throw new \RuntimeException(sprintf('Unable to write to file %s.', $this->filename));
        }

        fputs($file, $this->content);
        fclose($file);
    }
}
