<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CastingRecord;
use App\Models\Company;
use App\models\Tool;
use App\Models\Triming;
use App\Models\ProcessRecord;
use Illuminate\Support\Facades\DB;

class trimingController extends Controller
{
  public function index(Request $request)
    {
        $companies = Company::all();
        $tools = Tool::all();
$castings = CastingRecord::paginate(10); // paginate karvu hoy to
        // 🔹 Base query for Trimming records
        $recordsQuery = Triming::with(['company', 'tool']);

        // 🔹 Base query for ProcessRecord (only Trimings)
        $allRecordsQuery = ProcessRecord::with(['company', 'tool'])
            ->where('type_name', 'Trimings');

        // 🔍 Search filter
        if ($request->filled('search')) {
            $search = $request->search;

            // Triming records search
            $recordsQuery->where(function ($q) use ($search) {
                $q->where('machine_no', 'like', "%$search%")
                    ->orWhere('shift', 'like', "%$search%")
                    ->orWhere('operator_name1', 'like', "%$search%")
                    ->orWhere('operator_name2', 'like', "%$search%")
                    ->orWhereHas('tool', function ($t) use ($search) {
                        $t->where('name', 'like', "%$search%");
                    });
            });

            // Process records search
            $allRecordsQuery->where(function ($q) use ($search) {
                $q->where('machine_no', 'like', "%$search%")
                    ->orWhere('shift', 'like', "%$search%")
                    ->orWhere('machine_operator_name1', 'like', "%$search%")
                    ->orWhere('machine_operator_name2', 'like', "%$search%")
                    ->orWhereHas('tool', function ($t) use ($search) {
                        $t->where('name', 'like', "%$search%");
                    });
            });
        }

        // 📅 Date filter (from - to)
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from = $request->from_date;
            $to = $request->to_date;

            $recordsQuery->whereBetween('date', [$from, $to]);
            $allRecordsQuery->whereBetween('date', [$from, $to]);
        }

        // Pagination
        $records = $recordsQuery->paginate(10)->appends($request->all());
        $allRecords = $allRecordsQuery->paginate(10)->appends($request->all());

        return view("Production.Treeming", compact("companies", "tools", "records", "allRecords","castings"));
    }
    public function getQuantity($toolId)
    {
        $casting = CastingRecord::where('tool_type_id', $toolId)->first();

        if ($casting) {
            return response()->json([
                'success' => true,
                'data' => [
                    'quantity_kg' => $casting->quantity_kg,
                    'quantity_pcs' => $casting->quantity_pcs
                ]
            ]);
        }

        return response()->json(['success' => false], 404);
    }


    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'shift' => 'required|in:Day,Night',
            'machine_no' => 'required|in:1,2,3,5,6',
            'tool_type_id' => 'required|exists:tools,id',
            'operator_name1' => 'string|max:255',
            'operator_name2' => 'string|max:255',
            'quantity_kg' => 'required|numeric|min:0.01',
            'quantity_pcs' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // 1. Find matching Casting record
            $casting = CastingRecord::where('tool_type_id', $request->tool_type_id)->first();

            if (!$casting) {
                return redirect()->back()->with('error', 'No Casting found for selected component.');
            }

            // 2. Check if inspection has enough quantity
            if ($casting->quantity_pcs < $request->quantity_pcs) {
                return redirect()->back()->with('error', 'Not enough quantity available in inspection.');
            }

            // 3. Subtract quantity from inspection

            $casting->quantity_kg -= $request->quantity_kg;
            $casting->quantity_pcs -= $request->quantity_pcs;
            $casting->save();

            // 4. Check if Dispatch already exists for this tool_type
            $existingTriming = Triming::where('tool_type_id', $request->tool_type_id)->first();

            if ($existingTriming) {
                // Update quantity
                $existingTriming->date=$request->date;
                $existingTriming->shift=$request->shift;
                $existingTriming->machine_no=$request->machine_no;
                $existingTriming->operator_name1=$request->operator_name1;
                $existingTriming->operator_name2=$request->operator_name2;
                $existingTriming->quantity_kg + -$request->quantity_kg;
                $existingTriming->quantity_pcs += $request->quantity_pcs;
                $existingTriming->save();
            } else {
                // Create new dispatch record
                Triming::create($request->all());
            }

            // 5. Add to process_records table
            DB::table('process_records')->insert([
                'type_name' => 'Trimings',
                'date' => $request->date,
                'shift' => $request->shift,
                'machine_no' => $request->machine_no,
                'tool_type_id' => $request->tool_type_id,
                'machine_operator_name1' => $request->operator_name1,
                'machine_operator_name2' => $request->operator_name2,
                'quantity_kg' => $request->quantity_kg,
                'quantity_pcs' => $request->quantity_pcs,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('treeming.index')->with('success', 'Trimming record saved & casting updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }





    public function destroy($id)
    {
        // 1. Find the trimming record
        $trimming = Triming::find($id);

        if (!$trimming) {
            return redirect()->back()->with('error', 'Trimming record not found.');
        }

        // 2. Find the matching CastingRecord
        $casting = CastingRecord::where('machine_no', $trimming->machine_no)
            ->where('tool_type_id', $trimming->tool_type_id)
            ->where('machine_operator_name', $trimming->operator_name)
            ->first();

        if ($casting) {
            // 3. Add back the quantity to casting
            $casting->quantity_kg += $trimming->quantity_kg;
            $casting->quantity_pcs += $trimming->quantity_pcs;
            $casting->save();
        }

        // 4. Delete the trimming record
        $trimming->delete();

        // 5. Redirect to the trimming list page
        return redirect()->route('treeming.index')->with('success', 'Trimming record deleted & quantity restored to Casting.');
    }


}
