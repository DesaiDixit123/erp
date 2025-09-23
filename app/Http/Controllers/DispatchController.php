<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dispatch;
use App\Models\ProcessRecord;
use App\Models\Tool;
use App\Models\Inspection;
use Illuminate\Support\Facades\DB;



class DispatchController extends Controller
{
  public function index()
{
    $records = Dispatch::with('tool')->latest()->paginate(10);
    $tools = Tool::all();
    $inspectionAvailable = Inspection::sum('quantity_inspected');

    // 🟢 Inspection Records
    $inspectionRecords = Inspection::with('tool')->latest()->paginate(10);

    // 🔵 All Dispatch Process Records
    $allRecords = ProcessRecord::with(['company', 'tool'])
        ->where('type_name', 'Dispatchs')
        ->paginate(10);

    return view('Production.Dispatch', compact(
        'records',
        'tools',
        'allRecords',
        'inspectionAvailable',
        'inspectionRecords'
    ));
}

    public function create()
    {

        return view('inspection.create', compact('rejectionReasons'));
    }

public function store(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'tool_type_id' => 'required|exists:tools,id',
        'quantity_dispatch' => 'required|integer|min:1',
    ]);

    DB::beginTransaction();

    try {
        // 1. Find matching Inspection record
        $inspection = Inspection::where('tool_type_id', $request->tool_type_id)->first();

        if (!$inspection) {
            return redirect()->back()->with('error', 'No inspection found for selected component.');
        }

        // 2. Check if inspection has enough quantity
        if ($inspection->quantity_inspected < $request->quantity_dispatch) {
            return redirect()->back()->with('error', 'Not enough quantity available in inspection.');
        }

        // 3. Subtract quantity from inspection
        $inspection->quantity_inspected -= $request->quantity_dispatch;
        $inspection->save();

        // 4. Check if Dispatch already exists for this tool_type
        $existingDispatch = Dispatch::where('tool_type_id', $request->tool_type_id)->first();

        if ($existingDispatch) {
            // Update quantity
            $existingDispatch->quantity_dispatch += $request->quantity_dispatch;
            $existingDispatch->save();
        } else {
            // Create new dispatch record
            Dispatch::create($request->all());
        }

        // 5. Add to process_records table
        DB::table('process_records')->insert([
            'type_name' => 'Dispatch',
            'date' => $request->date,
            'quantity_dispatch' => $request->quantity_dispatch,
            'tool_type_id' => $request->tool_type_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::commit();

        return redirect()->route('dispatch.index')->with('success', 'Dispatch processed successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}


    public function destroy($id)
    {
        $dispatch = Dispatch::findOrFail($id);
        $dispatch->delete();

        return redirect()->back()->with('success', 'Dispatch record deleted.');
    }
}
