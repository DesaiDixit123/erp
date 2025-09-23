<?php

namespace App\Http\Controllers;

use App\Models\CastingRecord;
use App\Models\ProcessRecord;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Tool;
use Illuminate\Support\Facades\Validator;


class CastingRecordController extends Controller
{
    public function index(Request $request)
    {
        $companies = Company::all();
        $tools = Tool::all();

        // Base query for CastingRecord
        $recordsQuery = CastingRecord::with(['company', 'tool']);

        // Base query for ProcessRecord (only Castings)
        $allRecordsQuery = ProcessRecord::with(['company', 'tool'])
            ->where('type_name', 'Castings');

        // 🔍 Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $recordsQuery->where(function ($q) use ($search) {
                $q->where('machine_no', 'like', "%$search%")
                    ->orWhere('shift', 'like', "%$search%")
                    ->orWhere('machine_operator_name1', 'like', "%$search%")
                    ->orWhere('machine_operator_name2', 'like', "%$search%")
                    ->orWhereHas('tool', function ($t) use ($search) {
                        $t->where('name', 'like', "%$search%");
                    });
            });

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

        return view('Production.casting', compact('companies', 'tools', 'records', 'allRecords'));
    }

    public function getByCompany($companyId)
    {
        $tools = Tool::where('company_id', $companyId)->get();
        return response()->json($tools);
    }

    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'date' => 'required|date',
            'shift' => 'required|in:Day,Night',
            'machine_no' => 'required|in:1,2,3,5,6',
            'tool_type_id' => 'required|exists:tools,id',
            'machine_operator_name1' => 'string|max:255',
            'machine_operator_name2' => 'string|max:255',
            'quantity_kg' => 'required|numeric|min:0.01',
            'quantity_pcs' => 'required|integer|min:1',
        ]);

        // Check if record already exists in CastingRecord
        $existing = CastingRecord::where('tool_type_id', $request->tool_type_id)
            ->first();

        if ($existing) {
            // Update existing record by adding quantities
            $existing->shift = $request->shift;
            $existing->date = $request->date;
            $existing->machine_no = $request->machine_no;
            $existing->machine_operator_name1 = $request->machine_operator_name1;
            $existing->machine_operator_name2 = $request->machine_operator_name2;

            $existing->quantity_kg += $request->quantity_kg;
            $existing->quantity_pcs += $request->quantity_pcs;
            $existing->save();
        } else {
            // Create new record
            $existing = CastingRecord::create([
                'date' => $request->date,
                'shift' => $request->shift,
                'machine_no' => $request->machine_no,
                'tool_type_id' => $request->tool_type_id,
                'machine_operator_name1' => $request->machine_operator_name1,
                'machine_operator_name2' => $request->machine_operator_name2,
                'quantity_kg' => $request->quantity_kg,
                'quantity_pcs' => $request->quantity_pcs,
            ]);
        }

        \DB::table('process_records')->insert([
            'type_name' => 'Castings',
            'date' => $request->date,
            'shift' => $request->shift,
            'machine_no' => $request->machine_no,
            'tool_type_id' => $request->tool_type_id, // assuming this maps to component_id
            'machine_operator_name1' => $request->machine_operator_name1,
            'machine_operator_name2' => $request->machine_operator_name2,
            'quantity_kg' => $request->quantity_kg,
            'quantity_pcs' => $request->quantity_pcs,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Casting data saved successfully!');
    }
    public function destroyProcessRecord($id)
    {
        $record = ProcessRecord::findOrFail($id);
        $record->delete();

        return redirect()->back()->with('success', 'Record deleted successfully.');
    }

    public function destroy($id)
    {
        $record = CastingRecord::findOrFail($id);
        $record->delete();

        return redirect()->back()->with('success', 'Casting record deleted successfully!');
    }




    public function Treeming()
    {
        $companies = Company::all();
        $tools = Tool::all();
        $records = CastingRecord::with(['company', 'tool'])->paginate(10);


        return View("Production.Treeming", compact("companies", "tools", "records"));

    }




    public function TreemEdit($id)
    {
        $companies = Company::all();
        $tools = Tool::all();
        $record = CastingRecord::with(['company', 'tool'])->findOrFail($id); // ✅ single record

        return view('Production.updateTreeming', compact('companies', 'tools', 'record'));
    }



    public function updateTreem(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:casting_records,id',
            'treem' => 'required|integer|min:1',
        ]);

        $record = CastingRecord::findOrFail($request->id);

        $oldTreem = $record->treem ?? 0;
        $currentQuantity = $record->quantity;

        // Validation against available quantity
        if ($request->treem > $currentQuantity) {
            return response()->json([
                'success' => false,
                'message' => 'Treem value cannot exceed available quantity (' . $currentQuantity . ').'
            ], 422);
        }

        // Update record
        $record->treem = $oldTreem + $request->treem;
        $record->quantity = $currentQuantity - $request->treem;
        $record->save();

        return response()->json([
            'success' => true,
            'message' => 'Treem updated successfully!'
        ]);
    }


    public function updateInspection(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:casting_records,id',
            'inspection' => 'required|integer|min:1',
        ]);

        $record = CastingRecord::findOrFail($request->id);

        $availableTreem = $record->treem ?? 0;
        $inspectionValue = $request->inspection;

        // Validation check
        if ($inspectionValue > $availableTreem) {
            return response()->json([
                'success' => false,
                'message' => 'Inspection value cannot exceed available Treem quantity (' . $availableTreem . ').'
            ], 422);
        }

        // Update logic
        $record->inspection = ($record->inspection ?? 0) + $inspectionValue;
        $record->treem = $availableTreem - $inspectionValue;
        $record->save();

        return response()->json([
            'success' => true,
            'message' => 'Inspection updated successfully!'
        ]);
    }


    public function Inspection()
    {
        $companies = Company::all();
        $tools = Tool::all();
        $records = CastingRecord::with(['company', 'tool'])->paginate(10);


        return View("Production.Inspection", compact("companies", "tools", "records"));

    }
    public function Dispatch()
    {
        $companies = Company::all();
        $tools = Tool::all();
        $records = CastingRecord::with(['company', 'tool'])->paginate(10);


        return View("Production.Dispatch", compact("companies", "tools", "records"));

    }


    public function updateDispatch(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:casting_records,id',
            'dispatch' => 'required|integer|min:1',
        ]);

        $record = CastingRecord::findOrFail($request->id);
        $availableInspection = $record->inspection ?? 0;
        $dispatchQty = $request->dispatch;

        if ($dispatchQty > $availableInspection) {
            return response()->json([
                'success' => false,
                'message' => 'Dispatch quantity cannot exceed available Inspection quantity (' . $availableInspection . ').'
            ], 422);
        }

        $record->inspection = $availableInspection - $dispatchQty;
        $record->dispatch = ($record->dispatch ?? 0) + $dispatchQty;
        $record->save();

        return response()->json([
            'success' => true,
            'message' => 'Dispatch updated successfully!'
        ]);
    }

}
