<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feature Flags Admin</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Feature Flags</h1>
            <a
                href="{{ route('admin.feature-flags.create') }}"
                class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700"
            >
                Create feature flag
            </a>
        </div>

        @if(session('status'))
            <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-100 text-left text-slate-600">
                        <tr>
                            <th class="px-4 py-3 font-medium">ID</th>
                            <th class="px-4 py-3 font-medium">Key</th>
                            <th class="px-4 py-3 font-medium">Name</th>
                            <th class="px-4 py-3 font-medium">Type</th>
                            <th class="px-4 py-3 font-medium">Scope</th>
                            <th class="px-4 py-3 font-medium">Enabled</th>
                            <th class="px-4 py-3 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($featureFlags as $featureFlag)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">{{ $featureFlag->id }}</td>
                                <td class="px-4 py-3 font-mono text-xs">{{ $featureFlag->key }}</td>
                                <td class="px-4 py-3">{{ $featureFlag->name }}</td>
                                <td class="px-4 py-3">{{ $featureFlag->type }}</td>
                                <td class="px-4 py-3">{{ $featureFlag->scope }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2 py-1 text-xs {{ $featureFlag->enabled ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                        {{ $featureFlag->enabled ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <a class="text-indigo-600 hover:text-indigo-500" href="{{ route('admin.feature-flags.edit', $featureFlag->id) }}">Edit</a>
                                        <form action="{{ route('admin.feature-flags.destroy', $featureFlag->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-rose-600 hover:text-rose-500" type="submit" onclick="return confirm('Delete this feature flag?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-6 text-center text-slate-500" colspan="8">No feature flags found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5 flex items-center gap-3">
            @if(($meta['current_page'] ?? 1) > 1)
                <a
                    class="rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 hover:bg-slate-100"
                    href="{{ route('admin.feature-flags.index', ['page' => ($meta['current_page'] - 1)]) }}"
                >
                    Previous
                </a>
            @endif

            @if(($meta['current_page'] ?? 1) < ($meta['last_page'] ?? 1))
                <a
                    class="rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 hover:bg-slate-100"
                    href="{{ route('admin.feature-flags.index', ['page' => ($meta['current_page'] + 1)]) }}"
                >
                    Next
                </a>
            @endif
        </div>
    </div>
</body>
</html>
