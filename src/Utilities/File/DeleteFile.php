<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

use Ifrost\Common\Interfaces\Executable;

class DeleteFile implements Executable
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
        if (!is_file($this->filename)) {
            return;
        }

        try {
            unlink($this->filename) ?: throw new \RuntimeException();
        } catch (\Exception) {
            throw new \RuntimeException(sprintf('Unable remove file "%s". ', $this->filename));
        }
    }
}
