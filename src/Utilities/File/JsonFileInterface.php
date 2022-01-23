<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

interface JsonFileInterface extends FileInterface
{
    /**
     * @param array<string, mixed> $data
     * @param int<1, max>          $depth
     */
    public function create(array $data = [], int $flags = 0, int $depth = 512): void;

    /**
     * @param array<string, mixed> $data
     * @param int<1, max>          $depth
     */
    public function overwrite(array $data, int $flags = 0, int $depth = 512): void;

    /**
     * @phpstan-param int<1, max> $depth
     *
     * @return array<string|int, mixed>
     */
    public function read(bool $associative = true, int $flags = 0, int $depth = 512): array;
}
