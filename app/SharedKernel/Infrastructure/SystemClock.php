<?php

namespace App\SharedKernel\Infrastructure;

use App\SharedKernel\Domain\Clock;

final class SystemClock implements Clock
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now');
    }
}
