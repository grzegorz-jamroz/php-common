<?php

declare(strict_types=1);

namespace Ifrost\Common\Interfaces;

interface Acquirable
{
    public function acquire(): mixed;
}
