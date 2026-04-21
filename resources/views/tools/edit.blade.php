<!DOCTYPE html>
<html>

<head>
    <title>Edit Tool</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-lg">

        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Edit Tool</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
                @foreach ($errors->all() as $error)
                    <p>- {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('tools.update', $tool->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="font-semibold">Name</label>
                <input type="text" name="name" value="{{ old('name', $tool->name) }}"
                    class="w-full border rounded px-3 py-2 mt-1">
            </div>

            <div class="mb-4">
                <label class="font-semibold">Category</label>
                <input type="text" name="category" value="{{ old('category', $tool->category) }}"
                    class="w-full border rounded px-3 py-2 mt-1">
            </div>

            <div class="mb-4">
                <label class="font-semibold">Website</label>
                <input type="url" name="website" value="{{ old('website', $tool->website) }}"
                    class="w-full border rounded px-3 py-2 mt-1">
            </div>

            <div class="mb-4">
                <label class="font-semibold">Description</label>
                <textarea name="description" rows="4"
                    class="w-full border rounded px-3 py-2 mt-1">{{ old('description', $tool->description) }}</textarea>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('tools.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">
                    Back
                </a>

                <button class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">
                    Update
                </button>
            </div>
        </form>

    </div>

</body>

</html>