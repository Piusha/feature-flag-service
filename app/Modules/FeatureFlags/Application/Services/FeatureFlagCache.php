<?php

namespace App\Modules\FeatureFlags\Application\Services;

use App\Modules\FeatureFlags\Application\Contracts\FeatureFlagCacheInterface;
use App\Modules\FeatureFlags\Domain\ValueObjects\EvaluationContext;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\Log;

class FeatureFlagCache implements FeatureFlagCacheInterface
{
    private const VERSION_KEY = 'flags:version';

    public function __construct(private readonly CacheRepository $cache) {}

    public function keyForContext(EvaluationContext $context): string
    {
        $version = (string) $this->cache->get(self::VERSION_KEY, '1');

        return "flags:v{$version}";
    }

    public function getOrRemember(EvaluationContext $context, int $ttlSeconds, callable $resolver): array
    {
        $key = $this->keyForContext($context);

        /** @var array $cached */
        $cached = $this->cache->remember($key, $ttlSeconds, $resolver);

        return $cached;
    }

    public function invalidateAllContexts(): void
    {

        $version = (int) $this->cache->get(self::VERSION_KEY, 1);
        Log::info('Invalidating all contexts. Version: ' . $version);
        $this->cache->forever(self::VERSION_KEY, $version + 1);
    }
}
