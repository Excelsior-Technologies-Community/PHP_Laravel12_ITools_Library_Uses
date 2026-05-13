<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs - {{ $tool->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-gray-50 to-indigo-50 min-h-screen antialiased">

    <div class="max-w-4xl mx-auto py-12 px-4">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div>
                <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                    <i class="fa-solid fa-clock-rotate-left text-indigo-600"></i>
                    Activity Logs
                </h2>
                <p class="text-gray-500 mt-1 font-medium">Tracking history for <span class="text-indigo-600 font-bold">{{ $tool->name }}</span></p>
            </div>

            <a href="{{ route('tools.index') }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-2xl font-bold shadow-sm transition-all transform hover:-translate-x-1">
                <i class="fa-solid fa-arrow-left"></i> Back to Library
            </a>
        </div>

        @if(!$changes || $changes->count() == 0)
            <div class="bg-white border-2 border-dashed border-gray-200 rounded-3xl p-12 text-center shadow-inner">
                <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-database text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">No History Available</h3>
                <p class="text-gray-500 max-w-xs mx-auto mt-2">Changes will appear here once you start updating the tool details.</p>
            </div>
        @else

            <div class="space-y-8 relative">
                <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gray-200 hidden md:block"></div>

                @foreach($changes as $log)
                    <div class="relative pl-0 md:pl-16">
                        <div class="hidden md:block absolute left-6 top-6 w-4 h-4 rounded-full bg-indigo-500 border-4 border-white shadow-sm z-10"></div>
                        
                        <div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-gray-100 hover:border-indigo-200 transition-all">
                            
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex flex-col sm:row justify-between items-start sm:items-center gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="bg-indigo-100 text-indigo-700 text-xs font-black px-3 py-1 rounded-full uppercase tracking-tighter">
                                        Update Event
                                    </span>
                                </div>
                                <div class="flex items-center gap-3 text-gray-400">
                                    <i class="fa-regular fa-calendar-check text-sm"></i>
                                    <span class="text-xs font-bold font-mono tracking-tight text-gray-500">
                                        {{ $log->created_at->format('M d, Y • h:i A') }}
                                    </span>
                                    <span class="text-[10px] bg-gray-200 text-gray-600 px-2 py-0.5 rounded-md font-black">
                                        {{ $log->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-6 space-y-4">
                                @foreach($log->changes as $field => $change)
                                    <div class="group flex flex-col md:flex-row md:items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-transparent hover:border-indigo-100 hover:bg-white transition-all">
                                        <div class="md:w-40 flex-shrink-0">
                                            <span class="text-xs font-black text-indigo-400 uppercase tracking-widest block mb-1">Field Name</span>
                                            <span class="font-bold text-gray-800">{{ str_replace('_', ' ', ucfirst($field)) }}</span>
                                        </div>

                                        <div class="flex-grow flex items-center flex-wrap gap-3">
                                            <div class="bg-red-50 text-red-600 px-3 py-1.5 rounded-xl text-sm border border-red-100 line-through decoration-red-300">
                                                {{ $change['old'] ?? 'Null' }}
                                            </div>
                                            
                                            <i class="fa-solid fa-circle-chevron-right text-gray-300 animate-pulse"></i>
                                            
                                            <div class="bg-green-50 text-green-700 px-3 py-1.5 rounded-xl text-sm border border-green-100 font-bold shadow-sm">
                                                {{ $change['new'] }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 flex justify-center">
                <div class="bg-white px-6 py-3 rounded-2xl shadow-md border border-gray-100">
                    {{ $changes->links() }}
                </div>
            </div>

        @endif

    </div>

</body>
</html>