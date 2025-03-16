<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Receipt;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    // Show the form for creating a new receipt
    public function create_receipt(Invoice $invoice)
    {
        // Check if the invoice is already fully paid
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.show', $invoice->id)
                             ->with('error', 'Cannot create a receipt for a fully paid invoice.');
        }
        return view('invoices.receipt', compact('invoice'));
    }

    // Store a newly created receipt
    public function store(Request $request, Invoice $invoice)
    {

        $invoice = Invoice::find($request->invoice_id);
        // Check if the invoice is already fully paid
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.show', $invoice->id)
                             ->with('error', 'Cannot create a receipt for a fully paid invoice.');
        }

        $validatedData = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount_paid' => 'required|numeric',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        if ($invoice->remaining_balance > $request->amount_paid) {
            return redirect()->route('invoices.show', $invoice->id)
                             ->with('error', 'The amount paid cannot exceed the remaining balance.');
        }
        // Create the receipt
        $receipt = Receipt::create([
            'invoice_id' => $validatedData['invoice_id'],
            'amount_paid' => $validatedData['amount_paid'],
            'payment_date' => $validatedData['payment_date'],
            'payment_method' => $validatedData['payment_method'],
            'reference_number' => $validatedData['reference_number'],
            'notes' => $validatedData['notes'],
        ]);
        //$receipt = $invoice->receipts()->create($validatedData);

        // Update the remaining balance
        $invoice->decrement('remaining_balance', $receipt->amount_paid);

        // Update the invoice status
        if ($invoice->remaining_balance <= 0) {
            $invoice->update(['status' => 'paid']);
        } else {
            $invoice->update(['status' => 'partially_paid']);
        }

        return redirect()->route('invoices.show', $request->invoice_id)->with('success', 'Receipt created successfully.');
    }
}