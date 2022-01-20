<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

use Ifrost\Common\Acquirable;

class GetDirectoryPathFromFilename implements Acquirable
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
            throw new \InvalidArgumentException(sprintf('Invalid filename "%s". Filename has to contain at least one Trailing Slash "/" character.', $this->filename));
        }

        if ($pos === 0) {
            return '/';
        }

        return substr($this->filename, 0, $pos);
    }
}
