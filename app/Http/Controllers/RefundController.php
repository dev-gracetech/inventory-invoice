<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Refund;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    // Show the form for creating a new refund
    public function create_refund(Invoice $invoice)
    {
        return view('invoices.refund', compact('invoice'));
    }

    // Store a newly created refund
    public function store(Request $request, Invoice $invoice)
    {
        $validatedData = $request->validate([
            'amount_refunded' => 'required|numeric',
            'refund_date' => 'required|date',
            'refund_method' => 'required|string',
        ]);

        $totalPaid = $invoice->receipts()->sum('amount_paid');
        $totalRefunded = $invoice->refunds()->sum('amount_refunded');
        $maxRefundable = $totalPaid - $totalRefunded;
        if ($validatedData['amount_refunded'] > $maxRefundable) {
            return redirect()->route('invoices.show', $invoice->id)
                             ->with('error', 'The amount refunded cannot exceed the maximum refundable amount.');
        }

        // Create the refund
        $refund = $invoice->refunds()->create($validatedData);

        // Update the invoice balance
        $invoice->increment('remaining_balance', $refund->amount_refunded);

        // Update the invoice status
        if ($invoice->remaining_balance > 0) {
            $invoice->update(['status' => 'partially_paid']);
        }

        return redirect()->route('invoices.show', $invoice->id)
                         ->with('success', 'Refund created successfully.');
    }
}
