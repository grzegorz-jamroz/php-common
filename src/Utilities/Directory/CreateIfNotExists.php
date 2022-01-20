<?php
declare(strict_types=1);

namespace Ifrost\Common\Utilities\Directory;

use Ifrost\Common\HandleInterface;

class CreateIfNotExists implements HandleInterface
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function handle(): void
    {
        if (file_exists($this->path)) {
            return;
        }

        if (!mkdir($this->path, 0777, true)) {
            throw new \Exception(sprintf('Unable to create directory "%s".', $this->path));
        }
    }
}
