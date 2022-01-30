<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

class JsonFile extends File implements JsonFileInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(array $data = [], int $flags = 0, int $depth = 512): void
    {
        $json = json_encode($data, $flags, $depth) ?: '';

        (new CreateFileIfNotExists($this->filename, $json))->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function overwrite(array $data, int $flags = 0, int $depth = 512): void
    {
        $json = json_encode($data, $flags, $depth) ?: '';

        (new OverwriteFile($this->filename, $json))->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function read(bool $associative = true, int $flags = 0, int $depth = 512): array
    {
        $content = (new ReadFile($this->filename))->acquire();
        $data = json_decode($content, $associative, $depth, $flags);

        return is_array($data) ? $data : [];
    }
}
