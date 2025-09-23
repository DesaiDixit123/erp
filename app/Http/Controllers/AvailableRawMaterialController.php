<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AvailableRawMaterial;
use App\Models\RawMaterialType;
class AvailableRawMaterialController extends Controller
{
     public function index()
    {
        $available = AvailableRawMaterial::with('rawMaterialType')->paginate(10);
        $types = RawMaterialType::all(); // dropdown ma jova mate

        return view('raw_material.available', compact('available', 'types'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'raw_material_type_id' => 'required|exists:raw_material_types,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $existing = AvailableRawMaterial::where('raw_material_type_id', $data['raw_material_type_id'])->first();

        if ($existing) {
            // Quantity update karo
            $existing->quantity += $data['quantity'];
            $existing->save();
        } else {
            // નવો રેકોર્ડ બનાવો
            AvailableRawMaterial::create($data);
        }

        return redirect()->route('available.index')->with('success', 'Raw material stock updated successfully.');
    }

    public function edit($id)
{
    $available = AvailableRawMaterial::findOrFail($id);
    $types = RawMaterialType::all();

    return view('raw_material.available_edit', compact('available', 'types'));
}

public function update(Request $request, $id)
{
    $data = $request->validate([
        'raw_material_type_id' => 'required|exists:raw_material_types,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $available = AvailableRawMaterial::findOrFail($id);
    $available->update($data);

    return redirect()->route('available.index')->with('success', 'Raw material updated successfully.');
}

public function destroy($id)
{
    $available = AvailableRawMaterial::findOrFail($id);
    $available->delete();

    return redirect()->route('available.index')->with('success', 'Raw material deleted successfully.');
}

}
