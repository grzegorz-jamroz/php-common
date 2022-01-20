<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

use Ifrost\Common\Executable;

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

        if (file_exists($this->filename)) {
        }

        $dirPath = $this->getDirectoryPath();

        if (file_put_contents($this->filename, '') === false) {
            throw new \RuntimeException(sprintf('Unable to create file "%s".', $this->filename));
        }
    }

    private function getDirectoryPath()
    {
        $pos = strrpos($this->filename, '/');

        if ($pos === false) {
            throw new \InvalidArgumentException(sprintf('Invalid filename. "%s"', $this->filename));
        }

        return substr($this->filename, 0, $pos);
    }
}
