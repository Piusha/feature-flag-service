<?php

namespace App\Modules\FeatureFlags\Domain\ValueObjects;

final class FlagSchedule
{
    public function __construct(
        private readonly ?\DateTimeImmutable $startsAt,
        private readonly ?\DateTimeImmutable $expiresAt,
    ) {
    }

    public function startsAt(): ?\DateTimeImmutable
    {
        return $this->startsAt;
    }

    public function expiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function isBeforeStart(\DateTimeImmutable $now): bool
    {
        return $this->startsAt !== null && $now < $this->startsAt;
    }

    public function isAfterExpiry(\DateTimeImmutable $now): bool
    {
        return $this->expiresAt !== null && $now > $this->expiresAt;
    }
}
