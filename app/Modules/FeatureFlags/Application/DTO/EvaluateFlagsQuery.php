<?php

namespace App\Modules\FeatureFlags\Application\DTO;

use App\Modules\FeatureFlags\Domain\ValueObjects\EvaluationContext;

final class EvaluateFlagsQuery
{
    public function __construct(public readonly EvaluationContext $context)
    {
    }
}
