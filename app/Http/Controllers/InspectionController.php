<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspection;
use App\Models\CastingRecord;
use App\Models\Triming;
use App\Models\ProcessRecord;
use App\Models\Tool;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\DB;
class InspectionController extends Controller
{
public function index(Request $request)
{
    $queryInspection = Inspection::query();
    $queryTrimming = Triming::query();
    $queryAllRecords = ProcessRecord::with(['company', 'tool'])
        ->where('type_name', 'Inspections');

    // 🔹 Search Filter
    if ($request->filled('search')) {
        $search = $request->input('search');

        // Inspection
        $queryInspection->where(function($q) use ($search) {
            $q->whereHas('tool', function($toolQ) use ($search) {
                $toolQ->where('name', 'like', "%$search%");
            })
            ->orWhere('rejection_reason', 'like', "%$search%");
        });

        // Trimming
        $queryTrimming->where(function($q) use ($search) {
            $q->whereHas('tool', function($toolQ) use ($search) {
                $toolQ->where('name', 'like', "%$search%");
            });
        });

        // All Process Records
        $queryAllRecords->where(function($q) use ($search) {
            $q->whereHas('tool', function($toolQ) use ($search) {
                $toolQ->where('name', 'like', "%$search%");
            })
            ->orWhere('reason_of_rejected', 'like', "%$search%");
        });
    }

    // 🔹 Date Range Filter
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $from = $request->input('from_date');
        $to   = $request->input('to_date');

        $queryInspection->whereBetween('date', [$from, $to]);
        $queryTrimming->whereBetween('date', [$from, $to]);
        $queryAllRecords->whereBetween('date', [$from, $to]); 
    }

    $records     = $queryInspection->latest()->paginate(10)->withQueryString();
    $trimmings   = $queryTrimming->latest()->paginate(10)->withQueryString();
    $tools       = Tool::all();
    $allRecords  = $queryAllRecords->latest()->paginate(10)->withQueryString();

    return view('Production.Inspection', compact('records', 'tools', 'allRecords', 'trimmings'));
}



    public function create()
    {
        $rejectionReasons = ['Non-Filling', 'Trimming', 'Casting', 'Blow hole', 'Sound test'];
        return view('inspection.create', compact('rejectionReasons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'tool_type_id' => 'required|exists:tools,id',
            'quantity_inspected' => 'required|integer',
            'ok_quantity' => 'required|integer',
            'rejected_quantity' => 'required|integer',
            'rejection_reason' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            // 1. Find matching Inspection record
            $trimming = Triming::where('tool_type_id', $request->tool_type_id)->first();

            if (!$trimming) {
                return redirect()->back()->with('error', 'No Trimming found for selected component.');
            }

            // 2. Check if inspection has enough quantity
            if ($trimming->quantity_inspected < $request->quantity_dispatch) {
                return redirect()->back()->with('error', 'Not enough quantity available in inspection.');
            }

            // 2. Check if trimming has enough quantity
            if ($request->quantity_inspected > $trimming->quantity_pcs) {
                return redirect()->back()->with('error', 'Inspected quantity cannot exceed available trimming quantity (' . $trimming->quantity_pcs . ')');
            }


            // 3. Subtract quantity from inspection
            $trimming->quantity_pcs -= $request->quantity_inspected;
            $trimming->save();

            // 4. Check if Dispatch already exists for this tool_type
            $existingInspection = Inspection::where('tool_type_id', $request->tool_type_id)->first();

            if ($existingInspection) {


                $existingInspection->date = $request->date;
                $existingInspection->ok_quantity = $request->ok_quantity;
                $existingInspection->rejected_quantity = $request->rejected_quantity;
                $existingInspection->rejection_reason = $request->rejection_reason;
                // Update quantity
                $existingInspection->quantity_inspected += $request->quantity_inspected;
                $existingInspection->save();
            } else {
                // Create new dispatch record
                Inspection::create($request->all());
            }

            // 5. Add to process_records table
            DB::table('process_records')->insert([
                'type_name' => 'Inspections',
                'date' => $request->date,

                'quantity_inspected' => $request->quantity_inspected,
                'tool_type_id' => $request->tool_type_id,
                'ok_number' => $request->ok_quantity,
                'rejected_number' => $request->rejected_quantity,
                'reason_of_rejected' => $request->rejection_reason,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('inspection.index')->with('success', 'Inspection saved');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        $inspection = Inspection::findOrFail($id);
        $inspection->delete();

        return redirect()->back()->with('success', 'Inspection record deleted.');
    }
}
