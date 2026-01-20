<!DOCTYPE html>
<html>
<head>
    <title>ITools Library</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">

<div class="max-w-7xl mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">ITools Library</h2>
        <a href="{{ route('tools.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">
            Add New Tool
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded p-4">
        @if ($tools->count() > 0)
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="text-left px-4 py-2">Name</th>
                        <th class="text-left px-4 py-2">Category</th>
                        <th class="text-left px-4 py-2">Website</th>
                        <th class="text-left px-4 py-2">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tools as $tool)
                        <tr>
                            <td class="border px-4 py-2">{{ $tool->name }}</td>
                            <td class="border px-4 py-2">{{ $tool->category }}</td>
                            <td class="border px-4 py-2">
                                @if($tool->website)
                                    <a href="{{ $tool->website }}" target="_blank" class="text-blue-500 underline">
                                        Visit
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="border px-4 py-2">{{ $tool->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No tools found.</p>
        @endif
    </div>
</div>

</body>
</html>
