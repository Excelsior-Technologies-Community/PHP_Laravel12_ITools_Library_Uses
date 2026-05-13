<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Tool - ITools Library</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen antialiased">

    <div class="max-w-3xl mx-auto py-12 px-4">
        <div class="bg-white shadow-2xl rounded-3xl p-8 border border-gray-100">
            
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">🚀 Add New Tool</h2>
                    <p class="text-gray-500 mt-1">Expand your development toolkit</p>
                </div>
                <a href="{{ route('tools.index') }}" class="flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold transition-colors">
                    <i class="fa-solid fa-circle-arrow-left text-xl"></i> Back to Library
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-4 rounded-xl mb-8 shadow-sm">
                    <div class="flex items-center mb-2">
                        <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                        <span class="font-bold">Please fix the following errors:</span>
                    </div>
                    <ul class="text-sm list-disc list-inside space-y-1 ml-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('tools.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-gray-700 font-bold mb-2 ml-1">Tool Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full border border-gray-200 rounded-2xl px-5 py-4 focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all placeholder-gray-300 shadow-sm"
                        placeholder="e.g. Visual Studio Code">
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2 ml-1">Category</label>
                        <input type="text" name="category" value="{{ old('category') }}" required
                            class="w-full border border-gray-200 rounded-2xl px-5 py-4 focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all placeholder-gray-300 shadow-sm" 
                            placeholder="e.g. Editor / Framework">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2 ml-1">Website (optional)</label>
                        <input type="url" name="website" value="{{ old('website') }}"
                            class="w-full border border-gray-200 rounded-2xl px-5 py-4 focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all placeholder-gray-300 shadow-sm" 
                            placeholder="https://example.com">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2 ml-1">Description</label>
                    <textarea name="description" rows="5" required
                        class="w-full border border-gray-200 rounded-2xl px-5 py-4 focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all placeholder-gray-300 shadow-sm resize-none"
                        placeholder="What does this tool do?">{{ old('description') }}</textarea>
                </div>

                <div class="pt-4 flex items-center gap-4">
                    <button type="submit" class="flex-grow bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl shadow-xl hover:shadow-blue-200 transform hover:-translate-y-1 transition-all flex justify-center items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Save Tool to Library
                    </button>
                    
                    <a href="{{ route('tools.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-4 px-8 rounded-2xl transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>