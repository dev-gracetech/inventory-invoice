<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // Display a listing of the invoices
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.index', compact('invoices'));
    }

    // Show the form for creating a new invoice
    public function create()
    {
        $products = Product::all();
        $customers = Customer::all();
        return view('invoices.create', compact('products','customers'));
    }

    // Store a newly created invoice in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'invoice_number' => 'required|unique:invoices',
            'customer' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'products' => 'required|array', // Array of product IDs
            'quantities' => 'required|array', // Array of quantities
        ]);

        // Create the invoice
        $invoice = Invoice::create([
            'invoice_number' => $validatedData['invoice_number'],
            'customer_id' => $validatedData['customer'],
            'invoice_date' => $validatedData['invoice_date'],
            'due_date' => $validatedData['due_date'],
            'total_amount' => $validatedData['total_amount'], // Use the total amount from the form
        ]);

        $invoice->update(['remaining_balance' => $invoice->total_amount]);
        
        // Attach products to the invoice
        foreach ($validatedData['products'] as $index => $productId) {
            $product = Product::findOrFail($productId);
            $quantity = $validatedData['quantities'][$index];
            $unitPrice = $product->selling_price;
            $totalPrice = $quantity * $unitPrice;

            $invoice->products()->attach($productId, [
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
            ]);
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    // Display the specified invoice
    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    // Show the form for editing the specified invoice
    public function edit(Invoice $invoice)
    {
        $products = Product::all(); // Fetch all products for the dropdown
        return view('invoices.edit', compact('invoice', 'products'));
    }

    // Update the specified invoice in the database
    public function update(Request $request, Invoice $invoice)
    {
        $validatedData = $request->validate([
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'products' => 'required|array', // Array of product IDs
            'quantities' => 'required|array', // Array of quantitie
        ]);

        // Update the invoice
        $invoice->update([
            'invoice_date' => $validatedData['invoice_date'],
            'due_date' => $validatedData['due_date'],
            'total_amount' => $validatedData['total_amount'], // Use the total amount from the form
        ]);

        // Sync products (remove old items and add new ones)
        $invoice->products()->detach(); // Remove all existing items
        
        foreach ($validatedData['products'] as $index => $productId) {
            $product = Product::findOrFail($productId);
            $quantity = $validatedData['quantities'][$index];
            $unitPrice = $product->selling_price;
            $totalPrice = $quantity * $unitPrice;

            $invoice->products()->attach($productId, [
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
            ]);
        }

        //$invoice->update($validatedData);
        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    // Remove the specified invoice from the database
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    public function markAsPaid(Request $request, Invoice $invoice)
    {
        $validatedData = $request->validate([
            'amount_paid' => 'required|numeric',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
        ]);

        // Create a receipt
        $invoice->receipts()->create([
            'amount_paid' => $validatedData['amount_paid'],
            'payment_method' => $validatedData['payment_method'],
            'payment_date' => $validatedData['payment_date'],
        ]);

        // Update invoice status
        $invoice->update(['status' => 'Paid']);

        return redirect()->route('invoices.show', $invoice->id)->with('success', 'Invoice marked as paid.');
    }
}