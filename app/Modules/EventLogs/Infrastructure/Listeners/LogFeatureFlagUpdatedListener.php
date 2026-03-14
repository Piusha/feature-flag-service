<?php

namespace App\Modules\EventLogs\Infrastructure\Listeners;

use App\Modules\EventLogs\Application\Actions\StoreEventLogAction;
use App\Modules\EventLogs\Infrastructure\Mappers\FeatureFlagEventLogDataMapper;
use App\Modules\FeatureFlags\Domain\Events\FeatureFlagUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

final class LogFeatureFlagUpdatedListener implements ShouldQueue
{
    public string $queue = 'event-logs';

    public function __construct(
        private readonly StoreEventLogAction $storeEventLog,
        private readonly FeatureFlagEventLogDataMapper $mapper,
    ) {}

    public function handle(FeatureFlagUpdated $event): void
    {
        ($this->storeEventLog)($this->mapper->fromUpdated($event));
    }
}
