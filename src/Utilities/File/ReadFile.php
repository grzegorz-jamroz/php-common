<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

use Ifrost\Common\Interfaces\Acquirable;

class ReadFile implements Acquirable
{
    /**
     * @param string $filename fully path to file
     */
    public function __construct(private string $filename)
    {
    }

    public function acquire(): string
    {
        if (!is_file($this->filename)) {
            throw new \RuntimeException(sprintf('Unable to read file. File %s not exist.', $this->filename));
        }

        try {
            $content = file_get_contents($this->filename);

            return $content !== false ? $content : throw new \RuntimeException();
        } catch (\Exception) {
            throw new \RuntimeException(sprintf('Unable to read content of file %s.', $this->filename));
        }
    }
}
