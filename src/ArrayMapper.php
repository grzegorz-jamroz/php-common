<?php

declare(strict_types=1);

namespace Ifrost\Common;

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
        uasort($array, function ($a, $b) use ($fieldName, $direction) {
            $a = $a[$fieldName];
            $b = $b[$fieldName];

            if ($a === $b) {
                return 0;
            }

            if ('DESC' === $direction) {
                return ($a > $b) ? -1 : 1;
            }

            return ($a < $b) ? -1 : 1;
        });

        return $array;
    }
}
