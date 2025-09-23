<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaterialIssued;
use App\Models\RawMaterialType;
use App\Models\Inwarding;
class MaterialIssuedController extends Controller
{
    public function index()
    {
        $rawMaterials = RawMaterialType::all();

        // Calculate stock for each material
        foreach ($rawMaterials as $material) {
            $material->available_quantity = Inwarding::where('raw_material_type_id', $material->id)->sum('quantity');
        }

        $issuedMaterials = MaterialIssued::with('rawMaterialType')->latest()->paginate(10);

        return view('MaterialIssued.materialIssued', compact('rawMaterials', 'issuedMaterials'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'raw_material_type_id' => 'required|exists:raw_material_types,id',
            'issue_date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'shift' => 'required|in:Day,Night',
        ]);

        // STEP 1: Total available stock in Inwarding
        $totalStock = Inwarding::where('raw_material_type_id', $data['raw_material_type_id'])
            ->sum('quantity');

        if ($totalStock < $data['quantity']) {
            return back()->with('error', 'Issued quantity exceeds available stock.');
        }

        // STEP 2: Check if same material already issued for same date & shift
        $existing = MaterialIssued::where('raw_material_type_id', $data['raw_material_type_id'])
            ->where('issue_date', $data['issue_date'])
            ->where('shift', $data['shift'])
            ->first();

        if ($existing) {
            // ✅ Update existing row
            $existing->quantity += $data['quantity'];
            $existing->save();
        } else {
            // ✅ Create new row
            MaterialIssued::create($data);
        }

        // STEP 3: Deduct stock from Inwarding table (FIFO)
        $remainingQty = $data['quantity'];
        $inwardings = Inwarding::where('raw_material_type_id', $data['raw_material_type_id'])
            ->where('quantity', '>', 0)
            ->orderBy('purchase_date')
            ->get();

        foreach ($inwardings as $inwarding) {
            if ($remainingQty <= 0)
                break;

            if ($inwarding->quantity >= $remainingQty) {
                $inwarding->quantity -= $remainingQty;
                $inwarding->save();
                break;
            } else {
                $remainingQty -= $inwarding->quantity;
                $inwarding->quantity = 0;
                $inwarding->save();
            }
        }

        return back()->with('success', 'Material issued successfully and stock updated.');
    }



    public function edit($id)
    {
        $materialIssued = MaterialIssued::findOrFail($id);
        $rawMaterials = RawMaterialType::all();

        return view('MaterialIssued.edit', compact('materialIssued', 'rawMaterials'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'raw_material_type_id' => 'required|exists:raw_material_types,id',
            'issue_date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'shift' => 'required|in:Day,Night',
        ]);

        $material = MaterialIssued::findOrFail($id);
        $material->update($data);

        return redirect()->route('material-issued.index')->with('success', 'Material issued record updated successfully.');
    }

    public function destroy($id)
    {
        MaterialIssued::findOrFail($id)->delete();
        return back()->with('success', 'Record deleted.');
    }
}
