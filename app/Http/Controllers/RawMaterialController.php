<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RawMaterialType;
class RawMaterialController extends Controller
{
 public function index(Request $request)
{
    $query = RawMaterialType::query();

    // Search by name
    if ($request->has('search') && !empty($request->search)) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Filter by type
    if ($request->has('type') && in_array($request->type, ['Raw Material', 'Consumable'])) {
        $query->where('type', $request->type);
    }

    $types = $query->paginate(10)->appends($request->all());

    return view('raw_material.type', compact('types'));
}

public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|unique:raw_material_types,name',
        'measuring_unit' => 'nullable|string|max:50',
        'opening_stock' => 'nullable|numeric|min:0',
        'type' => 'nullable|in:Raw Material,Consumable', // optional type
    ]);

    RawMaterialType::create($data);

    return redirect()->route('raw_material.index')->with('success', 'Raw material added!');
}



    public function edit($id)
    {
        $type = RawMaterialType::findOrFail($id);
        return view('raw_material.edit', compact('type'));
    }

  public function update(Request $request, $id)
{
    $data = $request->validate([
        'name' => 'required|string|unique:raw_material_types,name,' . $id,
        'measuring_unit' => 'nullable|string|max:50',
        'opening_stock' => 'nullable|numeric|min:0',
        'type' => 'nullable|in:Raw Material,Consumable',
    ]);

    $type = RawMaterialType::findOrFail($id);
    $type->update($data);

    return redirect()->route('raw_material.index')->with('success', 'Raw material updated!');
}

    public function destroy($id)
    {
        $type = RawMaterialType::findOrFail($id);
        $type->delete();

        return redirect()->route('raw_material.index')->with('success', 'Raw material deleted!');
    }
}
