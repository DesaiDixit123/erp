<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\Tool;

use Illuminate\Support\Facades\Storage;
class ToolController extends Controller
{

  public function index(Request $request)
{
    $search = $request->input('search'); // search input lavsu

    $tools = Tool::with('company')
        ->when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%') // component name
                  ->orWhereHas('company', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%'); // customer name
                  });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    $tools->appends(['search' => $search]); // pagination ma search maintain

    $companies = Company::orderBy('name', 'ASC')->get();

    return view("Components.components", compact('tools', 'companies', 'search'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tools,name',
            'company_id' => 'required|exists:companies,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            // ✅ Custom error message for duplicate tool name
            'name.unique' => 'This component already exists. Please use a different name or update the existing one.',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();

            // Destination folder: public/storage/tools
            $destinationPath = base_path('/public/storage/tools');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Move image to the destination
            $image->move($destinationPath, $imageName);

            // Path to store in DB
            $imagePath = 'tools/' . $imageName;
        }

        $tool = new Tool();
        $tool->name = $request->name;
      $tool->company_id = $request->company_id;
        $tool->image = $imagePath;
        $tool->save();

        return back()->with('success', 'Component created successfully!');
    }



    public function edit($id)
    {
        $tools = Tool::findOrFail($id);
           $companies = Company::orderBy('name', 'ASC')->get();
        return view('Components.edit', compact('tools','companies'));
    }

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:tools,name,' . $id, // same name allow for same id
        'company_id' => 'required|exists:companies,id',
       
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $tool = Tool::findOrFail($id);

    // ✅ Image update logic
    if ($request->hasFile('image')) {
        // Delete old image
        if ($tool->image) {
            $oldImagePath = base_path('public/storage/' . $tool->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();

        $destinationPath = base_path('/public/storage/tools');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $image->move($destinationPath, $imageName);

        // Save relative path to DB
        $tool->image = 'tools/' . $imageName;
    }

    // ✅ Update fields
    $tool->name = $request->name;
    $tool->company_id = $request->company_id;


    $tool->save();

    return redirect()->route('components.index')->with('success', 'Component updated successfully.');
}





    public function destroy($id)
    {
        $tool = Tool::findOrFail($id);

        // Delete image
        if ($tool->image && Storage::disk('public')->exists($tool->image)) {
            Storage::disk('public')->delete($tool->image);
        }

        $tool->delete();

        return redirect()->route('components.index')->with('success', 'Component deleted successfully.');
    }
}
