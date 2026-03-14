<?php

namespace App\Modules\FeatureFlags\Presentation\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\FeatureFlags\Application\Contracts\ManageFeatureFlagsUseCaseInterface;
use App\Modules\FeatureFlags\Presentation\Http\Resources\FeatureFlagResource;
use App\Modules\FeatureFlags\Presentation\Http\Requests\StoreFeatureFlagRequest;
use App\Modules\FeatureFlags\Presentation\Http\Requests\UpdateFeatureFlagRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AdminFeatureFlagController extends Controller
{
    public function __construct(private readonly ManageFeatureFlagsUseCaseInterface $manageFeatureFlags)
    {
    }

    public function index(): JsonResponse
    {
        $page = (int) request()->query('page', 1);
        $result = $this->manageFeatureFlags->listPaginated(page: $page);
        $data = array_map(
            fn ($item): array => (new FeatureFlagResource($item))->resolve(),
            $result->items
        );

        return response()->json([
            'data' => $data,
            'meta' => [
                'total' => $result->total,
                'per_page' => $result->perPage,
                'current_page' => $result->currentPage,
                'last_page' => $result->lastPage,
            ],
        ]);
    }

    public function store(StoreFeatureFlagRequest $request): JsonResponse
    {
        $featureFlag = $this->manageFeatureFlags->create($request->toCommand());

        return response()->json([
            'data' => (new FeatureFlagResource($featureFlag))->resolve(),
        ], Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $featureFlag = $this->manageFeatureFlags->find($id);

        if ($featureFlag === null) {
            return response()->json(['message' => 'Feature flag not found.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => (new FeatureFlagResource($featureFlag))->resolve(),
        ]);
    }

    public function update(UpdateFeatureFlagRequest $request, int $id): JsonResponse
    {
        $updated = $this->manageFeatureFlags->update($request->toCommand());

        if ($updated === null) {
            return response()->json(['message' => 'Feature flag not found.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => (new FeatureFlagResource($updated))->resolve(),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $featureFlag = $this->manageFeatureFlags->find($id);

        if ($featureFlag === null) {
            return response()->json(['message' => 'Feature flag not found.'], Response::HTTP_NOT_FOUND);
        }

        $this->manageFeatureFlags->delete($id);

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
