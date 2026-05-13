<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ToolController extends Controller
{
    public function index(Request $request)
    {
        $query = Tool::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('category', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $tools = $query->latest()->paginate(10);

        return view('tools.index', compact('tools'));
    }

    public function create()
    {
        return view('tools.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'category'    => 'required',
            'description' => 'required',
            'website'     => 'nullable|url'
        ]);

        Tool::create($request->all());

        return redirect()->route('tools.index')->with('success', 'Tool created successfully');
    }

    public function edit($id)
    {
        $tool = Tool::findOrFail($id);
        return view('tools.edit', compact('tool'));
    }

    public function update(Request $request, $id)
    {
        $tool = Tool::findOrFail($id);

        $request->validate([
            'name'        => 'required',
            'category'    => 'required',
            'description' => 'required',
            'website'     => 'nullable|url'
        ]);

        $tool->update($request->all());

        return redirect()->route('tools.index')->with('success', 'Tool updated successfully');
    }

    public function destroy($id)
    {
        $tool = Tool::findOrFail($id);
        $tool->delete();

        return redirect()->route('tools.index')->with('success', 'Tool deleted successfully');
    }

    public function changelog($id)
    {
        $tool = Tool::findOrFail($id);
        
        $tableName = config('ltools.table_name_changelog_items', 'changelog_items');
        
        $changes = DB::table($tableName)
            ->where('model', Tool::class)
            ->where('model_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $changes->getCollection()->transform(function ($item) {
            $item->changes = json_decode($item->changes, true);
            $item->created_at = Carbon::parse($item->created_at);
            return $item;
        });

        return view('tools.changelog', compact('tool', 'changes'));
    }

    public function generateDescription(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $apiKey   = config('services.openai.key');
        $toolName = trim($request->name);

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'error'   => 'OPENAI_API_KEY missing in .env file.'
            ], 500);
        }

        $prompt = "Write a professional 2-sentence description for a software tool called \"{$toolName}\". Be concise and informative. Only return the description text, nothing else.";

        try {
            $response = Http::withoutVerifying()
                ->timeout(20)
                ->withHeaders([
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $apiKey,
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model'       => 'gpt-3.5-turbo',
                    'messages'    => [
                        [
                            'role'    => 'user',
                            'content' => $prompt,
                        ]
                    ],
                    'max_tokens'  => 200,
                    'temperature' => 0.7,
                ]);

            $status = $response->status();
            $result = $response->json();

            if ($status === 401) {
                return response()->json(['success' => false, 'error' => 'Invalid OpenAI API Key.'], 401);
            }

            if ($status === 429) {
                return response()->json(['success' => false, 'error' => 'OpenAI rate limit exceeded.'], 429);
            }

            if ($status === 200 && isset($result['choices']['message']['content'])) {
                $description = trim($result['choices']['message']['content']);
                return response()->json(['success' => true, 'description' => $description]);
            }

            Log::error('OpenAI unexpected response: ' . json_encode($result));
            return response()->json(['success' => false, 'error' => 'AI Generation failed.'], 500);

        } catch (\Exception $e) {
            Log::error('OpenAI Exception: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function checkHealth($id)
    {
        $tool = Tool::findOrFail($id);

        if (!$tool->website) {
            return response()->json(['status' => 'No URL', 'color' => 'gray']);
        }

        try {
            $response = Http::withoutVerifying()->timeout(5)->get($tool->website);
            $isUp     = $response->successful();

            return response()->json([
                'status' => $isUp ? 'Online' : 'Offline',
                'color'  => $isUp ? 'green' : 'red',
                'code'   => $response->status()
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Offline', 'color' => 'red']);
        }
    }

    public function compare(Request $request)
    {
        $ids   = $request->input('ids', []);
        $tools = Tool::whereIn('id', $ids)->get();

        return view('tools.compare', compact('tools'));
    }
}