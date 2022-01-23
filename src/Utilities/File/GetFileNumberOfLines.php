<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

use Ifrost\Common\Interfaces\Acquirable;

class GetFileNumberOfLines implements Acquirable
{
    /**
     * @var string fully path to file
     */
    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function acquire(): int
    {
        $count = 0;

        try {
            $file = fopen($this->filename, 'r') ?: throw new \RuntimeException();
        } catch (\Exception) {
            throw new \RuntimeException(sprintf('Unable to read file %s.', $this->filename));
        }

        while (!feof($file)) {
            fgets($file);
            ++$count;
        }

        fclose($file);

        return $count;
    }
}
