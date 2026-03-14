<?php

namespace App\Modules\FeatureFlags\Presentation\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\FeatureFlags\Application\Contracts\ManageFeatureFlagsUseCaseInterface;
use App\Modules\FeatureFlags\Presentation\Http\Requests\StoreFeatureFlagRequest;
use App\Modules\FeatureFlags\Presentation\Http\Requests\UpdateFeatureFlagRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FeatureFlagAdminPageController extends Controller
{
    public function __construct(private readonly ManageFeatureFlagsUseCaseInterface $manageFeatureFlags)
    {
    }

    public function index(): View
    {
        $page = (int) request()->query('page', 1);
        $result = $this->manageFeatureFlags->listPaginated(page: $page);

        return view('admin.feature_flags.index', [
            'featureFlags' => $result->items,
            'meta' => [
                'current_page' => $result->currentPage,
                'last_page' => $result->lastPage,
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.feature_flags.create');
    }

    public function store(StoreFeatureFlagRequest $request): RedirectResponse
    {
        $this->manageFeatureFlags->create($request->toCommand());

        return redirect()->route('admin.feature-flags.index')
            ->with('status', 'Feature flag created successfully.');
    }

    public function edit(int $id): View
    {
        $featureFlag = $this->manageFeatureFlags->find($id);
        abort_if($featureFlag === null, 404);

        return view('admin.feature_flags.edit', ['featureFlag' => $featureFlag]);
    }

    public function update(UpdateFeatureFlagRequest $request, int $id): RedirectResponse
    {
        $updated = $this->manageFeatureFlags->update($request->toCommand());
        abort_if($updated === null, 404);

        return redirect()->route('admin.feature-flags.index')
            ->with('status', 'Feature flag updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $featureFlag = $this->manageFeatureFlags->find($id);
        abort_if($featureFlag === null, 404);

        $this->manageFeatureFlags->delete($id);

        return redirect()->route('admin.feature-flags.index')
            ->with('status', 'Feature flag deleted successfully.');
    }
}
