<?php

declare(strict_types=1);

namespace Ifrost\Common;

interface Acquirable
{
    public function acquire(): mixed;
}
