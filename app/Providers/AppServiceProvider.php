<?php

namespace App\Providers;

use App\Modules\CarDamageReports\Application\Contracts\ManageCarDamageReportsUseCaseInterface;
use App\Modules\CarDamageReports\Application\UseCases\ManageCarDamageReportsUseCase;
use App\Modules\CarDamageReports\Domain\Repositories\CarDamageReportRepository;
use App\Modules\CarDamageReports\Infrastructure\Repositories\EloquentCarDamageReportRepository;
use App\Modules\EventLogs\Domain\Repositories\EventLogRepository;
use App\Modules\EventLogs\Infrastructure\Repositories\EloquentEventLogRepository;
use App\Modules\FeatureFlags\Application\Contracts\AssertFeatureEnabledUseCaseInterface;
use App\Modules\FeatureFlags\Application\Contracts\EvaluateFeatureFlagsUseCaseInterface;
use App\Modules\FeatureFlags\Application\Contracts\FeatureFlagCacheInterface;
use App\Modules\FeatureFlags\Application\Contracts\FeatureFlagEvaluatorInterface;
use App\Modules\FeatureFlags\Application\Contracts\ManageFeatureFlagsUseCaseInterface;
use App\Modules\FeatureFlags\Application\Contracts\PercentageRolloutServiceInterface;
use App\Modules\FeatureFlags\Application\Services\FeatureFlagCache;
use App\Modules\FeatureFlags\Application\Services\FeatureFlagEvaluator;
use App\Modules\FeatureFlags\Application\Services\PercentageRolloutService;
use App\Modules\FeatureFlags\Application\UseCases\AssertFeatureEnabledUseCase;
use App\Modules\FeatureFlags\Application\UseCases\EvaluateFeatureFlagsUseCase;
use App\Modules\FeatureFlags\Application\UseCases\ManageFeatureFlagsUseCase;
use App\Modules\FeatureFlags\Domain\Repositories\FeatureFlagRepository;
use App\Modules\FeatureFlags\Infrastructure\Repositories\EloquentFeatureFlagRepository;
use App\SharedKernel\Domain\Clock;
use App\SharedKernel\Infrastructure\SystemClock;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Clock::class, SystemClock::class);

        $this->app->bind(PercentageRolloutServiceInterface::class, PercentageRolloutService::class);
        $this->app->bind(FeatureFlagEvaluatorInterface::class, FeatureFlagEvaluator::class);
        $this->app->bind(FeatureFlagCacheInterface::class, FeatureFlagCache::class);
        $this->app->bind(EvaluateFeatureFlagsUseCaseInterface::class, EvaluateFeatureFlagsUseCase::class);
        $this->app->bind(ManageFeatureFlagsUseCaseInterface::class, ManageFeatureFlagsUseCase::class);
        $this->app->bind(AssertFeatureEnabledUseCaseInterface::class, AssertFeatureEnabledUseCase::class);
        $this->app->bind(ManageCarDamageReportsUseCaseInterface::class, ManageCarDamageReportsUseCase::class);

        $this->app->bind(FeatureFlagRepository::class, EloquentFeatureFlagRepository::class);
        $this->app->bind(CarDamageReportRepository::class, EloquentCarDamageReportRepository::class);
        $this->app->bind(EventLogRepository::class, EloquentEventLogRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
