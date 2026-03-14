<?php

namespace App\Modules\FeatureFlags\Domain\Events;

readonly class FeatureFlagEvaluationDenied
{
    /**
     * @param array<string, mixed>|null $context
     */
    public function __construct(
        public int $aggregateId,
        public string $featureFlagKey,
        public string $reason,
        public ?string $actorId,
        public ?string $actorType,
        public ?array $context,
        public \DateTimeImmutable $occurredAt,
    ) {}
}
