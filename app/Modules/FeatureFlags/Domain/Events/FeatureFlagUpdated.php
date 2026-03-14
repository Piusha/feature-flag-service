<?php

namespace App\Modules\FeatureFlags\Domain\Events;

readonly class FeatureFlagUpdated
{
    /**
     * @param array<string, mixed> $changes
     * @param array<string, mixed>|null $context
     */
    public function __construct(
        public int $aggregateId,
        public string $featureFlagKey,
        public ?string $actorId,
        public ?string $actorType,
        public array $changes,
        public ?array $context,
        public \DateTimeImmutable $occurredAt,
    ) {}
}
