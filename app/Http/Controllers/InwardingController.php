<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inwarding;
use App\Models\RawMaterialType;
use App\Models\Vendor;
class InwardingController extends Controller
{
    public function index()
    {
        $inwardings = Inwarding::with(['rawMaterialType', 'vendor'])->latest()->get();
        $rawMaterials = RawMaterialType::all();
        $vendors = Vendor::all();
        return view('InWardings.inwardings', compact('inwardings', 'rawMaterials', 'vendors'));
    }

    public function create()
    {
        $rawMaterials = RawMaterialType::all();
        $vendors = Vendor::all();
        return view('inwardings.create', compact('rawMaterials', 'vendors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'raw_material_type_id' => 'required|exists:raw_material_types,id',
            'purchase_date' => 'required|date',
            'vendor_id' => 'required|exists:vendors,id',
            'number_of_pcs' => 'nullable|integer|min:0',
            'quantity' => 'required|integer|min:0',
        ]);

        // Try to find existing entry with same raw_material_type_id and vendor_id
        $existing = Inwarding::where('raw_material_type_id', $data['raw_material_type_id'])
            ->where('vendor_id', $data['vendor_id'])
            ->first();

        if ($existing) {
            // Update existing entry: increase quantity and number_of_pcs
            $existing->quantity += $data['quantity'];
            $existing->number_of_pcs += $data['number_of_pcs'] ?? 0; // if null, add 0
            $existing->purchase_date = $data['purchase_date']; // optional: update latest date
            $existing->save();
        } else {
            // No existing match — create new entry
            Inwarding::create($data);
        }

        return redirect()->route('inwardings.index')->with('success', 'Inwarding entry processed successfully.');
    }


    public function edit($id)
    {
        $inwarding = Inwarding::findOrFail($id);
        $rawMaterials = RawMaterialType::all();
        $vendors = Vendor::all();

        return view('InWardings.edit', compact('inwarding', 'rawMaterials', 'vendors'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'raw_material_type_id' => 'required|exists:raw_material_types,id',
            'purchase_date' => 'required|date',
            'vendor_id' => 'required|exists:vendors,id',
            'number_of_pcs' => 'nullable|integer|min:0',
            'quantity' => 'required|integer|min:0',
        ]);

        $inwarding = Inwarding::findOrFail($id);

        // Add new values to existing ones
        $inwarding->number_of_pcs += $request->input('number_of_pcs', 0);
        $inwarding->quantity += $request->input('quantity');
        $inwarding->purchase_date = $request->purchase_date;
        $inwarding->raw_material_type_id = $request->raw_material_type_id;
        $inwarding->vendor_id = $request->vendor_id;

        $inwarding->save();

        return redirect()->route('inwardings.index')->with('success', 'Inwarding entry updated successfully.');
    }


    public function destroy($id)
    {
        $inwarding = Inwarding::findOrFail($id);
        $inwarding->delete();

        return redirect()->route('inwardings.index')->with('success', 'Inwarding entry deleted successfully.');
    }

    public function AvailableRawMaterials()
    {
        $inwardings = Inwarding::with(['rawMaterialType', 'vendor'])->latest()->get();
        $rawMaterials = RawMaterialType::all();
        $vendors = Vendor::all();

        return view('InWardings.availableRawMaterials', compact('inwardings', 'rawMaterials', 'vendors'));
    }



}
