<?php

namespace App\Modules\CarDamageReports\Domain\ValueObjects;

use InvalidArgumentException;

final class ReportReferenceNumber
{
    public function __construct(private readonly string $value)
    {
        if ($value === '') {
            throw new InvalidArgumentException('Reference number cannot be empty.');
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
