<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConsumableRawMaterial;
use App\Models\AvailableRawMaterial;
use Illuminate\Support\Facades\DB;
use App\Models\RawMaterialType;
use Illuminate\Support\Facades\View;
class ConsumableRawMaterialController extends Controller
{

    public function index()
    {
        $consumables = ConsumableRawMaterial::with('rawMaterialType')->paginate(10);
               $availables = AvailableRawMaterial::with('rawMaterialType')->paginate(10);
      $types = RawMaterialType::with('availableRawMaterial')->get();
        return view('raw_material.consumable', compact('consumables', 'types','availables'));
    }



    public function store(Request $request)
    {
        $data = $request->validate([
            'raw_material_type_id' => 'required|exists:raw_material_types,id',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $available = AvailableRawMaterial::where('raw_material_type_id', $data['raw_material_type_id'])->first();

            if (!$available) {
                return redirect()->back()->withErrors(['message' => 'Available record not found.']);
            }

            if ($available->quantity < $data['quantity']) {
                return redirect()->back()->withErrors([
                    'message' => 'Not enough stock. Available: ' . $available->quantity
                ]);
            }

            // Deduct from available
            $available->quantity -= $data['quantity'];
            $available->save();

            // Check if consumable entry already exists
            $existingConsumable = ConsumableRawMaterial::where('raw_material_type_id', $data['raw_material_type_id'])->first();

            if ($existingConsumable) {
                // Update quantity if entry exists
                $existingConsumable->quantity += $data['quantity'];
                $existingConsumable->save();
            } else {
                // Create new entry
                ConsumableRawMaterial::create($data);
            }

            DB::commit();

            return redirect()->route('consumable.index')->with('success', 'Material consumed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['message' => 'Something went wrong!']);
        }
    }


    public function edit($id)
    {
        $consumable = ConsumableRawMaterial::findOrFail($id);
        $types = RawMaterialType::all();

        return view('raw_material.consumable_edit', compact('consumable', 'types'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'raw_material_type_id' => 'required|exists:raw_material_types,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $consumable = ConsumableRawMaterial::findOrFail($id);
        $oldQuantity = $consumable->quantity;
        $typeId = $consumable->raw_material_type_id;

        DB::beginTransaction();

        try {
            // Revert old quantity back to Available
            $available = AvailableRawMaterial::where('raw_material_type_id', $typeId)->first();
            if ($available) {
                $available->quantity += $oldQuantity;
            }

            // Deduct new quantity
            if ($available->quantity < $data['quantity']) {
                return redirect()->back()->withErrors(['message' => 'Not enough stock. Available: ' . $available->quantity]);
            }

            $available->quantity -= $data['quantity'];
            $available->save();

            // Update the consumable
            $consumable->update($data);

            DB::commit();
            return redirect()->route('consumable.index')->with('success', 'Material updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['message' => 'Something went wrong!']);
        }
    }
    public function destroy($id)
    {
        $consumable = ConsumableRawMaterial::findOrFail($id);

        DB::beginTransaction();

        try {
            // Return the consumed quantity back to available
            $available = AvailableRawMaterial::where('raw_material_type_id', $consumable->raw_material_type_id)->first();
            if ($available) {
                $available->quantity += $consumable->quantity;
                $available->save();
            }

            $consumable->delete();

            DB::commit();
            return redirect()->route('consumable.index')->with('success', 'Material deleted and stock adjusted.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['message' => 'Something went wrong!']);
        }
    }




}
