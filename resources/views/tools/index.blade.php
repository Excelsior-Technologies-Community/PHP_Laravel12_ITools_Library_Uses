<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITools Library - Stack Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .tool-card:hover .compare-checkbox { opacity: 1; }
        .compare-checkbox { transition: opacity 0.2s; }
        @keyframes pulse-yellow { 0%, 100% { background-color: #fbbf24; } 50% { background-color: #f59e0b; } }
        .checking-pulse { animation: pulse-yellow 1s infinite; }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen font-sans antialiased">

    <div class="max-w-6xl mx-auto py-10 px-4">

        <div class="bg-white shadow-xl rounded-3xl p-8 mb-8 flex flex-col md:flex-row justify-between items-center gap-6 border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="bg-blue-600 p-4 rounded-2xl shadow-lg transform rotate-3">
                    <i class="fa-solid fa-screwdriver-wrench text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">ITools Library</h1>
                    <p class="text-gray-500 font-medium">Manage & Monitor Your Development Stack</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button onclick="compareTools()" id="compare-btn" 
                    class="hidden bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition-all flex items-center gap-2 transform hover:scale-105">
                    <i class="fa-solid fa-code-compare"></i> Compare Selected
                </button>
                <a href="{{ route('tools.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-blue-200 transform hover:-translate-y-1 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Add New Tool
                </a>
            </div>
        </div>

        <div class="bg-white p-2 rounded-2xl shadow-md mb-8 border border-gray-100 flex items-center overflow-hidden focus-within:ring-2 focus-within:ring-blue-400 transition-all">
            <form method="GET" action="{{ route('tools.index') }}" class="flex w-full">
                <div class="relative flex-grow">
                    <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" placeholder="Search by name, category or description..." value="{{ request('search') }}"
                        class="w-full pl-14 pr-4 py-4 bg-transparent text-gray-700 outline-none placeholder-gray-400 font-medium">
                </div>
                <button class="bg-gray-900 hover:bg-black text-white px-10 py-4 rounded-xl font-bold transition-colors m-1">
                    Search
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-xl mb-8 shadow-sm flex items-center gap-3 animate-bounce">
                <i class="fa-solid fa-circle-check text-green-500 text-xl"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if($tools->isEmpty())
            <div class="text-center py-20 bg-white rounded-3xl shadow-inner border-2 border-dashed border-gray-200">
                <i class="fa-solid fa-box-open text-6xl text-gray-300 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-500">No tools found</h2>
                <p class="text-gray-400">Try searching for something else or add a new tool.</p>
            </div>
        @else
            <div class="grid md:grid-cols-2 lg:grid-cols-2 gap-8" id="tools-grid">
                @foreach($tools as $tool)
                    <div class="tool-card group bg-white rounded-3xl shadow-md p-6 hover:shadow-2xl transition-all duration-300 border border-gray-100 relative overflow-hidden">
                        
                        <div class="absolute top-4 right-4">
                            <input type="checkbox" name="tool_ids[]" value="{{ $tool->id }}" onchange="toggleCompareBtn()"
                                class="compare-checkbox w-6 h-6 rounded-lg text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer shadow-sm">
                        </div>

                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <span class="text-xs font-black uppercase tracking-widest text-blue-600 bg-blue-50 px-3 py-1 rounded-full mb-2 inline-block">
                                    {{ $tool->category }}
                                </span>
                                <h3 class="text-2xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $tool->name }}</h3>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-2xl p-4 mb-6 space-y-3">
                            <div class="flex items-start gap-3">
                                <i class="fa-solid fa-align-left text-gray-400 mt-1"></i>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ Str::limit($tool->description, 110) }}</p>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-globe text-gray-400"></i>
                                <a href="{{ $tool->website }}" target="_blank" class="text-sm text-blue-500 font-bold hover:underline truncate">
                                    {{ str_replace(['http://', 'https://'], '', $tool->website) }}
                                </a>
                            </div>

                            <div class="flex items-center justify-between pt-2 border-t border-gray-200 mt-2">
                                <div class="flex items-center gap-2">
                                    <div id="health-dot-{{ $tool->id }}" class="w-3 h-3 rounded-full bg-gray-300"></div>
                                    <span id="health-text-{{ $tool->id }}" class="text-xs font-black text-gray-400 uppercase tracking-tighter">Initializing...</span>
                                </div>
                                <button onclick="checkHealth({{ $tool->id }})" class="text-xs font-black text-blue-600 hover:text-blue-800 transition-all uppercase">
                                    <i class="fa-solid fa-sync-alt me-1"></i> Re-check
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <div class="flex gap-2">
                                <a href="{{ route('tools.edit', $tool->id) }}"
                                    class="bg-white border border-gray-200 hover:border-blue-500 hover:text-blue-600 text-gray-600 px-4 py-2 rounded-xl text-sm font-bold shadow-sm transition-all flex items-center gap-2">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </a>

                                <form action="{{ route('tools.destroy', $tool->id) }}" method="POST"
                                    onsubmit="return confirm('Delete this tool from library?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-white border border-gray-200 hover:border-red-500 hover:text-red-600 text-gray-400 hover:text-red-600 px-4 py-2 rounded-xl text-sm transition-all">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>

                            <a href="{{ route('tools.changelog', $tool->id) }}"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2 rounded-xl text-sm font-bold flex items-center gap-2 transition-all">
                                <i class="fa-solid fa-history"></i> Logs
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-12">
            {{ $tools->links() }}
        </div>
    </div>

    <script>
        // FUNCTION: AJAX Health Checker
        function checkHealth(id) {
            const dot = $(`#health-dot-${id}`);
            const text = $(`#health-text-${id}`);
            
            dot.removeClass('bg-gray-300 bg-green-500 bg-red-500').addClass('checking-pulse');
            text.text('Pinging...').addClass('text-yellow-600');

            $.get(`/tools/health/${id}`, function(data) {
                dot.removeClass('checking-pulse').addClass(`bg-${data.color}-500`);
                text.text(data.status).removeClass('text-yellow-600 text-gray-400').addClass(`text-${data.color}-600`);
            }).fail(function() {
                dot.removeClass('checking-pulse').addClass('bg-red-500');
                text.text('Offline').addClass('text-red-600');
            });
        }

        // FUNCTION: Toggle Compare Button
        function toggleCompareBtn() {
            const checkedCount = $('.compare-checkbox:checked').length;
            if (checkedCount >= 2) {
                $('#compare-btn').fadeIn().removeClass('hidden');
            } else {
                $('#compare-btn').fadeOut();
            }
        }

        // FUNCTION: Compare Tools Logic
        function compareTools() {
            const selectedIds = $('.compare-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
            
            const queryParams = selectedIds.map(id => `ids[]=${id}`).join('&');
            window.location.href = `/tools/compare?${queryParams}`;
        }

        // AUTO-INIT: Load Health on Page Load
        $(document).ready(function() {
            @foreach($tools as $tool)
                setTimeout(() => { checkHealth({{ $tool->id }}); }, {{ $loop->index * 200 }}); // Staggered load for better UX
            @endforeach
        });
    </script>
</body>
</html>