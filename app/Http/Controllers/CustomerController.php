<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Display a listing of customers
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    // Store a newly created customer
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Customer::create($validatedData);
        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    // Update the specified customer
    public function update(Request $request, Customer $customer)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $customer->update($validatedData);
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    // Remove the specified customer
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function show_statement(Customer $customer)
    {
        // Fetch all invoices for the customer
        $invoices = $customer->invoices()->with(['receipts', 'refunds'])->get();

        // Calculate summary
        $totalInvoiced = $invoices->sum('total_amount');
        $totalPaid = $invoices->sum(function ($invoice) {
            return $invoice->receipts->sum('amount_paid');
        });
        $totalRefunded = $invoices->sum(function ($invoice) {
            return $invoice->refunds->sum('amount_refunded');
        });
        $outstandingBalance = $totalInvoiced - $totalPaid + $totalRefunded;

        return view('customers.statement', compact('customer', 'invoices', 'totalInvoiced', 'totalPaid', 'totalRefunded', 'outstandingBalance'));
    }
}