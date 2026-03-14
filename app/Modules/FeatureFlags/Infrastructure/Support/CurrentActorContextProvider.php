<?php

namespace App\Modules\FeatureFlags\Infrastructure\Support;

use App\Modules\FeatureFlags\Application\Contracts\ActorContextProviderInterface;
use App\Modules\FeatureFlags\Application\DTO\ActorContextData;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

final class CurrentActorContextProvider implements ActorContextProviderInterface
{
    public function __construct(private readonly AuthFactory $auth)
    {
    }

    public function resolve(): ActorContextData
    {
        $user = $this->auth->guard()->user();

        if ($user === null) {
            return new ActorContextData(actorId: null, actorType: null);
        }

        $actorId = method_exists($user, 'getAuthIdentifier')
            ? $user->getAuthIdentifier()
            : ($user->id ?? null);

        return new ActorContextData(
            actorId: $actorId !== null ? (string) $actorId : null,
            actorType: $user::class,
        );
    }
}
