<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\Arrays;

use Ifrost\Foundations\Acquirable;

class OrderArrayBy implements Acquirable
{
    /**
     * @param array<mixed, mixed> $array
     */
    public function __construct(
        private array $array,
        private string $fieldName,
        private string $direction = 'ASC',
    ) {
    }

    /**
     * @return array<mixed, mixed>
     */
    public function acquire(): array
    {
        return $this->orderBy(
            $this->array,
            $this->fieldName,
            $this->direction
        );
    }

    /**
     * @param array<mixed, mixed> $array
     *
     * @return array<mixed, mixed>
     */
    private function orderBy(
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
