<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

use Ifrost\Common\Interfaces\Acquirable;

class GetFileFullName implements Acquirable
{
    /**
     * @var string fully path to file
     */
    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function acquire(): string
    {
        $directoryPath = (new GetDirectoryPath($this->filename))->acquire();
        $length = strlen($directoryPath);

        if ($length > 1) {
            ++$length;
        }

        $fileName = substr($this->filename, $length, strlen($this->filename));

        return $fileName === false ? '' : $fileName;
    }
}
