<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

use Ifrost\Common\Interfaces\Acquirable;

class GetFileName implements Acquirable
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
        $fullName = (new GetFileFullName($this->filename))->acquire();
        $pos = strrpos($fullName, '.');

        if (
            $pos === false
            || $pos === 0
        ) {
            return $fullName;
        }

        return substr($fullName, 0, $pos);
    }
}
