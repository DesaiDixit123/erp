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
        // 'quantity_inspected' => 'required|integer|min:0',
        'non_filling' => 'nullable|integer|min:0',
        'trimming' => 'nullable|integer|min:0',
        'casting' => 'nullable|integer|min:0',
        'blow_hole' => 'nullable|integer|min:0',
        'sound_test' => 'nullable|integer|min:0',
    ]);

    DB::beginTransaction();

    try {
        // 1. Find trimming stock
        $trimming = Triming::where('tool_type_id', $request->tool_type_id)->first();
        if (!$trimming) {
            return redirect()->back()->with('error', 'No Trimming found for selected component.');
        }

        if ($request->quantity_inspected > $trimming->quantity_pcs) {
            return redirect()->back()
                ->with('error', 'Inspected quantity cannot exceed available trimming quantity (' . $trimming->quantity_pcs . ')');
        }

        // 2. Auto calculate rejection
        $totalRejected = 
            ($request->non_filling ?? 0) +
            ($request->trimming ?? 0) +
            ($request->casting ?? 0) +
            ($request->blow_hole ?? 0) +
            ($request->sound_test ?? 0);

        $okQty = $request->quantity_inspected - $totalRejected;
        if ($okQty < 0) $okQty = 0;

        // 3. Subtract from trimming
        $trimming->quantity_kg -= $request->ok_quantity;
        $trimming->save();

        // 4. Save or update inspection
        $inspection = Inspection::firstOrNew([
            'tool_type_id' => $request->tool_type_id,
            'date' => $request->date,
        ]);

        $inspection->ok_quantity += $request->ok_quantity;
        $inspection->non_filling = ($inspection->non_filling ?? 0) + ($request->non_filling ?? 0);
        $inspection->trimming    = ($inspection->trimming ?? 0) + ($request->trimming ?? 0);
        $inspection->casting     = ($inspection->casting ?? 0) + ($request->casting ?? 0);
        $inspection->blow_hole   = ($inspection->blow_hole ?? 0) + ($request->blow_hole ?? 0);
        $inspection->sound_test  = ($inspection->sound_test ?? 0) + ($request->sound_test ?? 0);
        $inspection->total_rejected = ($inspection->total_rejected ?? 0) + $totalRejected;
        $inspection->ok_quantity = ($inspection->ok_quantity ?? 0) + $okQty;

        $inspection->save();

        // 5. Add to process_records
        DB::table('process_records')->insert([
            'type_name' => 'Inspections',
            'date' => $request->date,
            'tool_type_id' => $request->tool_type_id,
            'rejected_number' => $totalRejected,
            'reason_of_rejected' => json_encode([
                'non_filling' => $request->non_filling,
                'trimming' => $request->trimming,
                'casting' => $request->casting,
                'blow_hole' => $request->blow_hole,
                'sound_test' => $request->sound_test,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::commit();
        return redirect()->route('inspection.index')->with('success', 'Inspection saved successfully.');

    } catch (\Exception $e) {
    DB::rollBack();
    dd($e->getMessage(), $e->getTraceAsString());
}

}



    public function destroy($id)
    {
        $inspection = Inspection::findOrFail($id);
        $inspection->delete();

        return redirect()->back()->with('success', 'Inspection record deleted.');
    }
}
