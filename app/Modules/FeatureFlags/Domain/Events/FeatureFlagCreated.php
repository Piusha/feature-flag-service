<?php

namespace App\Modules\FeatureFlags\Domain\Events;

use Carbon\CarbonImmutable;

readonly class FeatureFlagCreated
{
    /**
     * @param array<string, mixed>|null $context
     */
    public function __construct(
        public int $aggregateId,
        public string $featureFlagKey,
        public ?string $actorId,
        public ?string $actorType,
        public ?array $context,
        public CarbonImmutable $occurredAt,
    ) {}
}
