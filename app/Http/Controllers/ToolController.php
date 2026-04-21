<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    // LIST + SEARCH + PAGINATION
    public function index(Request $request)
    {
        $query = Tool::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('category', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $tools = $query->latest()->paginate(4);

        return view('tools.index', compact('tools'));
    }

    // CREATE PAGE
    public function create()
    {
        return view('tools.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'description' => 'required',
        ]);

        Tool::create($request->all());

        return redirect()->route('tools.index')
            ->with('success', 'Tool created successfully');
    }

    // EDIT
    public function edit($id)
    {
        $tool = Tool::findOrFail($id);
        return view('tools.edit', compact('tool'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $tool = Tool::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'description' => 'required',
        ]);

        $tool->update($request->all());

        return redirect()->route('tools.index')
            ->with('success', 'Tool updated successfully');
    }

    // DELETE
    public function destroy($id)
    {
        $tool = Tool::findOrFail($id);
        $tool->delete();

        return redirect()->route('tools.index')
            ->with('success', 'Tool deleted successfully');
    }

    // CHANGELOG (🔥 ltools feature)
    public function changelog($id)
    {
        $tool = Tool::findOrFail($id);
        $changes = $tool->changelogs;

        return view('tools.changelog', compact('tool', 'changes'));
    }
}