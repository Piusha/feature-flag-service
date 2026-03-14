@php($isEdit = isset($featureFlag))
@php($action = $isEdit ? route('admin.feature-flags.update', $featureFlag->id) : route('admin.feature-flags.store'))

<form method="POST" action="{{ $action }}" class="space-y-5">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Key</label>
            <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" type="text" name="key" value="{{ old('key', $featureFlag->key ?? '') }}" required>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Name</label>
            <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" type="text" name="name" value="{{ old('name', $featureFlag->name ?? '') }}" required>
        </div>
        <div class="md:col-span-2">
            <label class="mb-1 block text-sm font-medium text-slate-700">Description</label>
            <textarea class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" name="description" rows="3">{{ old('description', $featureFlag->description ?? '') }}</textarea>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Type</label>
            <select class="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" name="type" required>
                @foreach(['boolean', 'rule_based'] as $type)
                    <option value="{{ $type }}" @selected(old('type', $featureFlag->type ?? 'boolean') === $type)>
                        {{ $type }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Scope</label>
            <select class="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" name="scope" required>
                @foreach(['component', 'feature', 'page'] as $scope)
                    <option value="{{ $scope }}" @selected(old('scope', $featureFlag->scope ?? 'component') === $scope)>
                        {{ $scope }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Enabled</label>
            <select class="block w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" name="enabled" required>
                <option value="1" @selected((string) old('enabled', $featureFlag->enabled ?? true) === '1')>Yes</option>
                <option value="0" @selected((string) old('enabled', $featureFlag->enabled ?? true) === '0')>No</option>
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Rollout Percentage</label>
            <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" type="number" min="0" max="100" name="rollout_percentage" value="{{ old('rollout_percentage', $featureFlag->rolloutPercentage ?? '') }}">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Starts At</label>
            <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" type="datetime-local" name="starts_at" value="{{ old('starts_at', isset($featureFlag) && $featureFlag->startsAt ? \Carbon\Carbon::parse($featureFlag->startsAt)->format('Y-m-d\TH:i') : '') }}">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Expires At</label>
            <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" type="datetime-local" name="expires_at" value="{{ old('expires_at', isset($featureFlag) && $featureFlag->expiresAt ? \Carbon\Carbon::parse($featureFlag->expiresAt)->format('Y-m-d\TH:i') : '') }}">
        </div>
    </div>

    @if($errors->any())
        <ul class="rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div class="pt-2">
        <button class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500" type="submit">
            {{ $isEdit ? 'Update' : 'Create' }}
        </button>
    </div>
</form>
