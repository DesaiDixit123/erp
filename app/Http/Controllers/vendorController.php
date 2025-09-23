<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\vendor;


class vendorController extends Controller
{
public function index(Request $request)
{
    $search = $request->input('search'); // search input lavsu

    $vendors = Vendor::when($search, function($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // search query preserve karva mate pagination ma
    $vendors->appends(['search' => $search]);

    return view('Vendors.vendor', compact('vendors', 'search'));
}



public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|unique:vendors,name',
    ], [
        'name.unique' => 'This vendor already exists. Please use a different name or update the existing one.',
    ]);

    Vendor::create($data);

    return redirect()->route('vendor.index')->with('success', 'Vendor created successfully!');
}


    public function edit($id)
    {
        $vendor = vendor::findOrFail($id);
        return view('Vendors.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string',
        ]);

        $vendor = vendor::findOrFail($id);
        $vendor->update($data);

        return redirect()->route('vendor.index')->with('success', 'Vendor updated!');
    }

    public function destroy($id)
    {
        $vendor = vendor::findOrFail($id);
        $vendor->delete();

        return redirect()->route('vendor.index')->with('success', 'Vendor deleted!');
    }

}
