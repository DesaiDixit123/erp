<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
class CompanyController extends Controller
{

public function index(Request $request)
{
    $search = $request->input('search'); // input thi search value lavsu

    $companies = Company::when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // query string preserve karava mate (pagination ma next click karie tyare search missing na thay)
    $companies->appends(['search' => $search]);

    return view("Customer.customer", compact('companies', 'search'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:companies,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.unique' => 'This customer already exists. Please try again with a different name or update the existing record.',

        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();

            $destinationPath = base_path('/public/storage/companies');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);

            $imagePath = 'companies/' . $imageName;
        }

        $company = new Company();
        $company->name = $request->name;
        $company->image = $imagePath;
        $company->save();

        return back()->with('success', 'Customer created successfully!');
    }



    public function edit($id)
    {
        $companies = Company::findOrFail($id);
        return view('Customer.edit', compact('companies'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $company = Company::findOrFail($id);

        if ($request->hasFile('image')) {
            // Delete old image from public/storage/company_images
            $oldImagePath = base_path('storage/' . $company->image);
            if ($company->image && file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();

            $destinationPath = base_path('/public/storage/companies');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);

            // Store relative path for DB
            $company->image = 'companies/' . $imageName;
        }
        $company->name = $request->name;

        $company->save();

        return redirect()->route('companies.index')->with('success', 'Customer updated successfully.');
    }



    public function destroy($id)
    {
        $company = Company::findOrFail($id);

        // Delete image
        if ($company->image && Storage::disk('public')->exists($company->image)) {
            Storage::disk('public')->delete($company->image);
        }

        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Customer deleted successfully.');
    }
}
