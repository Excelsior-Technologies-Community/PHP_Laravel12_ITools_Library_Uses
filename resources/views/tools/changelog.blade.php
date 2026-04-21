<!DOCTYPE html>
<html>

<head>
    <title>Changelog</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <div class="max-w-4xl mx-auto py-10">

        <div class="flex justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                Changelog - {{ $tool->name }}
            </h2>

            <a href="{{ route('tools.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded">
                Back
            </a>
        </div>

        {{-- FIX: null-safe check --}}
        @if(empty($changes) || $changes->count() == 0)
            <div class="bg-yellow-100 p-4 rounded">
                No changes found
            </div>
        @else

            <div class="space-y-4">
                @foreach($changes as $log)
                                <div class="bg-white shadow rounded-lg p-5">

                                    <p class="text-sm text-gray-500 mb-2">
                                        {{ $log->created_at }}
                                    </p>

                                    <pre class="bg-gray-100 p-3 rounded text-sm">
                    {{ json_encode($log->changes, JSON_PRETTY_PRINT) }}
                                            </pre>

                                </div>
                @endforeach
            </div>

        @endif

    </div>

</body>

</html>