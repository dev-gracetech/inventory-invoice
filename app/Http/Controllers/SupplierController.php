<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    // Store a newly created supplier
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Supplier::create($validatedData);
        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    // Update the specified supplier
    public function update(Request $request, Supplier $supplier)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $supplier->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $supplier->update($validatedData);
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    // Remove the specified supplier
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
