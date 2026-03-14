<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Feature Flag</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Edit Feature Flag</h1>
            <a class="mt-2 inline-block text-sm text-indigo-600 hover:text-indigo-500" href="{{ route('admin.feature-flags.index') }}">
                Back to list
            </a>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            @include('admin.feature_flags._form', ['featureFlag' => $featureFlag])
        </div>
    </div>
</body>
</html>
