<?php

declare(strict_types=1);

namespace Ifrost\Common\Interface;

interface Acquirable
{
    public function acquire(): mixed;
}
