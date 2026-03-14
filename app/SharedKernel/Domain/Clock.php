<?php

namespace App\SharedKernel\Domain;

interface Clock
{
    public function now(): \DateTimeImmutable;
}
