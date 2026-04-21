<!DOCTYPE html>
<html>

<head>
    <title>ITools Library</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">

    <div class="max-w-6xl mx-auto py-10">

        <!-- HEADER -->
        <div class="bg-white shadow-lg rounded-2xl p-6 mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">🚀 ITools Library</h1>
                <p class="text-gray-500 text-sm">Manage your tools efficiently</p>
            </div>

            <a href="{{ route('tools.create') }}"
                class="bg-gradient-to-r from-blue-500 to-blue-700 hover:scale-105 transform transition text-white px-6 py-2 rounded-lg shadow">
                + Add Tool
            </a>
        </div>

        <!-- SEARCH -->
        <div class="bg-white p-4 rounded-xl shadow mb-6">
            <form method="GET" action="{{ route('tools.index') }}" class="flex gap-3">
                <input type="text" name="search" placeholder="🔍 Search tools..." value="{{ request('search') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none">

                <button class="bg-gray-800 hover:bg-black text-white px-6 py-2 rounded-lg shadow">
                    Search
                </button>
            </form>
        </div>

        <!-- SUCCESS MESSAGE -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-6 shadow">
                {{ session('success') }}
            </div>
        @endif

        <!-- CARD GRID -->
        <div class="grid md:grid-cols-2 gap-6">

            @foreach($tools as $tool)
                <div class="bg-white rounded-2xl shadow-md p-5 hover:shadow-xl transition duration-300">

                    <!-- TITLE -->
                    <div class="mb-3">
                        <h3 class="text-xl font-bold text-gray-800">{{ $tool->name }}</h3>
                        <span class="text-sm text-blue-600 bg-blue-100 px-2 py-1 rounded">
                            {{ $tool->category }}
                        </span>
                    </div>

                    <!-- FULL DETAILS -->
                    <div class="text-sm text-gray-600 mb-4 space-y-2">

                        <p>
                            📝 <b>Description:</b><br>
                            {{ $tool->description }}
                        </p>

                        <p>
                            🌐 <b>Website:</b>
                            <a href="{{ $tool->website }}" target="_blank" class="text-blue-600 hover:underline">
                                {{ $tool->website }}
                            </a>
                        </p>

                        <p>
                            📅 <b>Created:</b>
                            {{ $tool->created_at ? $tool->created_at->format('d M Y') : 'N/A' }}
                        </p>

                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="flex justify-between items-center">

                        <div class="flex gap-2">
                            <a href="{{ route('tools.edit', $tool->id) }}"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                ✏ Edit
                            </a>

                            <form action="{{ route('tools.destroy', $tool->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this tool?')">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                    🗑 Delete
                                </button>
                            </form>
                        </div>

                        <a href="{{ route('tools.changelog', $tool->id) }}"
                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                            📜 Logs
                        </a>

                    </div>

                </div>
            @endforeach

        </div>

        <!-- PAGINATION -->
        <div class="mt-8 flex justify-center">
            {{ $tools->links() }}
        </div>

    </div>

</body>

</html>