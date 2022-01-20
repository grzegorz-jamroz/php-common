<?php
declare(strict_types=1);

namespace Ifrost\Common;

interface HandleInterface
{
    /**
     * @throws \Exception
     */
    public function handle(): void;
}
