<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\Directory;

use Ifrost\Common\Interfaces\Acquirable;

class CountFilesInDirectory implements Acquirable
{
    /**
     * @param array<string, mixed> $options
     * @GetFilesFromDirectory - the same options
     */
    public function __construct(
        private string $directoryPath,
        private array $options = [],
    ) {
    }

    public function acquire(): int
    {
        return count((new GetFilesFromDirectory($this->directoryPath, $this->options))->acquire());
    }
}
