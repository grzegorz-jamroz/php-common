<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\Directory;

use Ifrost\Common\Interfaces\Acquirable;

class CountFilesAndDirectories implements Acquirable
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
        $numberOfFiles = (new CountFilesInDirectory($this->directoryPath, $this->options))->acquire();
        $numberOfSubDirectories = (new CountSubDirectoriesInDirectory($this->directoryPath, $this->options))->acquire();

        return $numberOfFiles + $numberOfSubDirectories;
    }
}
