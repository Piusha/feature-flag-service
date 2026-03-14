<?php

namespace App\SharedKernel\Application;

final class OperationResult
{
    public function __construct(
        public readonly int $status,
        public readonly array $body = [],
    ) {
    }
}
