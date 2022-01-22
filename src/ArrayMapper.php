<?php

declare(strict_types=1);

namespace Ifrost\Common;

use Ifrost\Common\Utilities\Arrays\OrderArrayBy;

class ArrayMapper
{
    /**
     * @param array<mixed, mixed> $array
     *
     * @return array<mixed, mixed>
     */
    public static function orderBy(
        array $array,
        string $fieldName,
        string $direction = 'ASC'
    ): array {
        return (new OrderArrayBy($array, $fieldName, $direction))->acquire();
    }
}
