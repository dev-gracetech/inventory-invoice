<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
   // Display a listing of the products
   public function index()
   {
       $products = Product::all();
       return view('products.index', compact('products'));
   }

   // Show the form for creating a new product
   public function create()
   {
       return view('products.create');
   }

   // Store a newly created product in the database
   public function store(Request $request)
   {
       $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'buying_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category' => 'nullable|string',
       ]);

       Product::create($validatedData);
       return redirect()->route('products.index')->with('success', 'Product created successfully.');
       //return response()->json(['success' => true, 'message' => 'Product created successfully.']);
   }

   // Display the specified product
   public function show(Product $product)
   {
       return view('products.show', compact('product'));
   }

   // Show the form for editing the specified product
   public function edit(Product $product)
   {
       //return view('products.edit', compact('product'));
       return response()->json(['product' => $product]);
   }

   // Update the specified product in the database
   public function update(Request $request, Product $product)
   {
       $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'buying_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category' => 'nullable|string',
       ]);

       $product->update($validatedData);
       //return redirect()->route('products.index')->with('success', 'Product updated successfully.');
       return response()->json(['success' => true, 'message' => 'Product updated successfully.']);
   }

   // Remove the specified product from the database
   public function destroy(Product $product)
   {
       $product->delete();
       return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
   }

   public function uploadImage(Request $request, Product $product)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/products'), $imageName);

            $stock->update(['image' => $imageName]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}
