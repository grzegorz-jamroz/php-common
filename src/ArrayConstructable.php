<?php

declare(strict_types=1);

namespace Ifrost\Common;

interface ArrayConstructable
{
    /**
     * @param array<string, mixed> $data
     */
    public static function createFromArray(array $data): static|self;
}
