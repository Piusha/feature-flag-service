<?php

namespace App\Providers;

use App\Modules\EventLogs\Infrastructure\Listeners\LogFeatureFlagCreatedListener;
use App\Modules\EventLogs\Infrastructure\Listeners\LogFeatureFlagDeletedListener;
use App\Modules\EventLogs\Infrastructure\Listeners\LogFeatureFlagEvaluationDeniedListener;
use App\Modules\EventLogs\Infrastructure\Listeners\LogFeatureFlagUpdatedListener;
use App\Modules\FeatureFlags\Domain\Events\FeatureFlagCreated;
use App\Modules\FeatureFlags\Domain\Events\FeatureFlagDeleted;
use App\Modules\FeatureFlags\Domain\Events\FeatureFlagEvaluationDenied;
use App\Modules\FeatureFlags\Domain\Events\FeatureFlagUpdated;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        FeatureFlagCreated::class => [
            LogFeatureFlagCreatedListener::class,
        ],
        FeatureFlagUpdated::class => [
            LogFeatureFlagUpdatedListener::class,
        ],
        FeatureFlagDeleted::class => [
            LogFeatureFlagDeletedListener::class,
        ],
        FeatureFlagEvaluationDenied::class => [
            LogFeatureFlagEvaluationDeniedListener::class,
        ],
    ];
}
