<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\Directory;

use Ifrost\Common\Interfaces\Acquirable;

class GetDirectoryParentPath implements Acquirable
{
    /**
     * @var string parent path for the directory
     */
    private string $directoryPath;

    public function __construct(string $directoryPath)
    {
        $this->directoryPath = $directoryPath;
    }

    public function acquire(): string
    {
        if ($this->directoryPath === '/') {
            return '/';
        }

        $pos = strrpos($this->directoryPath, '/');

        if ($pos === false) {
            $pos = strrpos($this->directoryPath, '\\');
        }

        if ($pos === false) {
            throw new \InvalidArgumentException(sprintf('Directory path has to contain at least one Trailing Slash "/" or "\" character. Invalid directory path "%s".', $this->directoryPath));
        }

        $length = strlen($this->directoryPath);

        if ($pos === $length - 1) {
            throw new \InvalidArgumentException(sprintf('Directory path has to contain string after last Trailing Slash "/" or "\" character. Invalid directory path "%s".', $this->directoryPath));
        }

        if ($pos === 0) {
            return '/';
        }

        return substr($this->directoryPath, 0, $pos);
    }
}
