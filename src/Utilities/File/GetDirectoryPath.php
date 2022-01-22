<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

use Ifrost\Common\Interfaces\Acquirable;

class GetDirectoryPath implements Acquirable
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
        $pos = strrpos($this->filename, '/');

        if ($pos === false) {
            $pos = strrpos($this->filename, '\\');
        }

        if ($pos === false) {
            throw new \InvalidArgumentException(sprintf('Filename has to contain at least one Trailing Slash "/" character. Invalid filename "%s".', $this->filename));
        }

        $length = strlen($this->filename);

        if ($pos === $length - 1) {
            throw new \InvalidArgumentException(sprintf('Filename has to contain string after last Trailing Slash "/" character. Invalid filename "%s".', $this->filename));
        }

        if ($length <= 1) {
            throw new \InvalidArgumentException(sprintf('Filename has to contain at least two characters. Invalid filename "%s".', $this->filename));
        }

        if ($pos === 0) {
            return '/';
        }

        return substr($this->filename, 0, $pos);
    }
}
