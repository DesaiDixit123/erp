<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tools;
use App\Models\Tool;


class ToolsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dispatches = Tools::with('component')->latest()->paginate(10);
        return view('', compact('dispatches'));
    }

    /**
     * Show the form for creating a new resource.
     */
public function create(Request $request)
{
    $tools = Tool::all();

    $searchComponent = $request->input('component');
    $searchType = $request->input('tool_type');
    $searchDate = $request->input('manufacturing_date');

    $dispatches = Tools::with('component')
        ->when($searchComponent, function ($query, $searchComponent) {
            $query->whereHas('component', function ($q) use ($searchComponent) {
                $q->where('name', 'like', '%' . $searchComponent . '%');
            });
        })
        ->when($searchType, function ($query, $searchType) {
            $query->where('component_type', $searchType);
        })
        ->when($searchDate, function ($query, $searchDate) {
            $query->whereDate('manufacturing_date', $searchDate);
        })
        ->latest()
        ->paginate(10);

    $dispatches->appends([
        'component' => $searchComponent,
        'tool_type' => $searchType,
        'manufacturing_date' => $searchDate
    ]);

    return view('Tools.AddTools', compact('tools', 'dispatches', 'searchComponent', 'searchType', 'searchDate'));
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'component_id' => 'required|exists:tools,id',
            'component_type' => 'nullable|in:Casting Tool,Trimming Tool',
            'tool_number' => 'nullable|string|max:255',
            'manufacturing_date' => 'nullable|date',

        ]);

        Tools::create($request->all());

        return redirect()->route('toools.index')
            ->with('success', 'Tool Dispatch created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dispatch = Tools::findOrFail($id);  // id thi dispatch fetch
        $tools = Tool::all();                      // all tools fetch
        return view('Tools.edit', compact('dispatch', 'tools'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'component_id' => 'required|exists:tools,id',
            'component_type' => 'nullable|in:Casting Tool,Trimming Tool',
            'tool_number' => 'nullable|string|max:255',
            'manufacturing_date' => 'nullable|date',

        ]);

        // Find dispatch by id
        $dispatch = Tools::findOrFail($id);

        // Update dispatch record
        $dispatch->update($request->all());

        return redirect()->route('toools.index')
            ->with('success', 'Tool Dispatch updated successfully!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $dispatch = Tools::findOrFail($id);
        $dispatch->delete();

        return redirect()->route('toools.index')
            ->with('success', 'Tool Dispatch deleted successfully!');
    }

}
