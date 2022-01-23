<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

class JsonFile extends File implements JsonFileInterface
{
    /**
     * @param array<string, mixed> $data
     * @param int<1, max>          $depth
     * @description The method will replace all file content with new content.
     * The method will create a new file if it does not exist with given content.
     * The method will create the missing directories if necessary.
     */
    public function overwrite(array $data, int $flags = 0, int $depth = 512): void
    {
        $json = json_encode($data, $flags, $depth) ?: '';

        (new OverwriteFile($this->filename, $json))->execute();
    }

    /**
     * @phpstan-param int<1, max> $depth
     *
     * @return array<string|int, mixed>
     * @description The method will return the contents of the file if it exists.
     */
    public function read(bool $associative = true, int $flags = 0, int $depth = 512): array
    {
        $content = (new ReadFile($this->filename))->acquire();
        $data = json_decode($content, $associative, $depth, $flags);

        return is_array($data) ? $data : [];
    }
}
