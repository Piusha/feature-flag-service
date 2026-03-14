<?php

namespace App\Modules\EventLogs\Infrastructure\Mappers;

use App\Modules\EventLogs\Application\DTO\StoreEventLogData;
use App\Modules\FeatureFlags\Domain\Events\FeatureFlagCreated;
use App\Modules\FeatureFlags\Domain\Events\FeatureFlagDeleted;
use App\Modules\FeatureFlags\Domain\Events\FeatureFlagEvaluationDenied;
use App\Modules\FeatureFlags\Domain\Events\FeatureFlagUpdated;

final class FeatureFlagEventLogDataMapper
{
    public function fromCreated(FeatureFlagCreated $event): StoreEventLogData
    {
        return new StoreEventLogData(
            eventName: 'feature_flag.created',
            aggregateType: 'feature_flag',
            aggregateId: (string) $event->aggregateId,
            actorId: $event->actorId,
            actorType: $event->actorType,
            context: $event->context,
            payload: [
                'feature_flag_key' => $event->featureFlagKey,
            ],
            occurredAt: $event->occurredAt,
        );
    }

    public function fromUpdated(FeatureFlagUpdated $event): StoreEventLogData
    {
        return new StoreEventLogData(
            eventName: 'feature_flag.updated',
            aggregateType: 'feature_flag',
            aggregateId: (string) $event->aggregateId,
            actorId: $event->actorId,
            actorType: $event->actorType,
            context: $event->context,
            payload: [
                'feature_flag_key' => $event->featureFlagKey,
                'changes' => $event->changes,
            ],
            occurredAt: $event->occurredAt,
        );
    }

    public function fromDeleted(FeatureFlagDeleted $event): StoreEventLogData
    {
        return new StoreEventLogData(
            eventName: 'feature_flag.deleted',
            aggregateType: 'feature_flag',
            aggregateId: (string) $event->aggregateId,
            actorId: $event->actorId,
            actorType: $event->actorType,
            context: $event->context,
            payload: [
                'feature_flag_key' => $event->featureFlagKey,
            ],
            occurredAt: $event->occurredAt,
        );
    }

    public function fromEvaluationDenied(FeatureFlagEvaluationDenied $event): StoreEventLogData
    {
        return new StoreEventLogData(
            eventName: 'feature_flag.evaluation_denied',
            aggregateType: 'feature_flag',
            aggregateId: (string) $event->aggregateId,
            actorId: $event->actorId,
            actorType: $event->actorType,
            context: $event->context,
            payload: [
                'feature_flag_key' => $event->featureFlagKey,
                'reason' => $event->reason,
            ],
            occurredAt: $event->occurredAt,
        );
    }
}
