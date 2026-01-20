<!DOCTYPE html>
<html>
<head>
    <title>Add New Tool</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">

<div class="max-w-3xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Add New Tool</h2>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tools.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold">Tool Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                class="w-full border rounded px-3 py-2 mt-1" placeholder="Enter tool name">
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Category</label>
            <input type="text" name="category" value="{{ old('category') }}"
                class="w-full border rounded px-3 py-2 mt-1" placeholder="Enter category">
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Website (optional)</label>
            <input type="url" name="website" value="{{ old('website') }}"
                class="w-full border rounded px-3 py-2 mt-1" placeholder="Enter website link">
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Description</label>
            <textarea name="description" rows="5"
                class="w-full border rounded px-3 py-2 mt-1" placeholder="Enter description">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded">
            Save Tool
        </button>
    </form>
</div>

</body>
</html>
