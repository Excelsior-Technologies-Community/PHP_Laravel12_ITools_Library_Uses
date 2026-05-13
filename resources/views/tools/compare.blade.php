<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tool Comparison - ITools Library</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-gray-50 to-indigo-50 min-h-screen antialiased p-4 md:p-10">

    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
            <div>
                <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                    <i class="fa-solid fa-code-compare text-blue-600"></i>
                    Tool Comparison
                </h2>
                <p class="text-gray-500 mt-1 font-medium">Side-by-side technical analysis</p>
            </div>

            <a href="{{ route('tools.index') }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-2xl font-bold shadow-sm transition-all">
                <i class="fa-solid fa-arrow-left"></i> Back to Library
            </a>
        </div>

        <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-900 text-white">
                            <th class="p-6 text-sm uppercase tracking-widest font-black w-48">Specifications</th>
                            @foreach($tools as $tool)
                                <th class="p-6 border-l border-gray-800 min-w-[250px]">
                                    <div class="flex flex-col">
                                        <span class="text-blue-400 text-xs font-bold uppercase mb-1">{{ $tool->category }}</span>
                                        <span class="text-xl font-extrabold">{{ $tool->name }}</span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-6 font-bold text-gray-700 bg-gray-50 flex items-center gap-2">
                                <i class="fa-solid fa-layer-group text-blue-500"></i> Category
                            </td>
                            @foreach($tools as $tool)
                                <td class="p-6 border-l border-gray-100">
                                    <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-black uppercase">
                                        {{ $tool->category }}
                                    </span>
                                </td>
                            @endforeach
                        </tr>
                        
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-6 font-bold text-gray-700 bg-gray-50 flex items-center gap-2">
                                <i class="fa-solid fa-quote-left text-blue-500"></i> Description
                            </td>
                            @foreach($tools as $tool)
                                <td class="p-6 border-l border-gray-100 text-sm text-gray-600 leading-relaxed italic">
                                    {{ $tool->description }}
                                </td>
                            @endforeach
                        </tr>

                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-6 font-bold text-gray-700 bg-gray-50 flex items-center gap-2">
                                <i class="fa-solid fa-calendar-day text-blue-500"></i> Added On
                            </td>
                            @foreach($tools as $tool)
                                <td class="p-6 border-l border-gray-100 text-sm font-medium text-gray-500">
                                    {{ $tool->created_at->format('M d, Y') }}
                                </td>
                            @endforeach
                        </tr>

                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-6 font-bold text-gray-700 bg-gray-50 flex items-center gap-2">
                                <i class="fa-solid fa-link text-blue-500"></i> Website
                            </td>
                            @foreach($tools as $tool)
                                <td class="p-6 border-l border-gray-100">
                                    @if($tool->website)
                                        <a href="{{ $tool->website }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 font-bold hover:text-blue-800 group transition-all">
                                            Visit Official Site
                                            <i class="fa-solid fa-external-link text-xs transform group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                                        </a>
                                    @else
                                        <span class="text-gray-400 italic">No link available</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 text-center text-gray-400 text-sm font-medium">
            <i class="fa-solid fa-circle-info me-1"></i> Data automatically compiled from your ITools Library.
        </div>
    </div>

</body>
</html>