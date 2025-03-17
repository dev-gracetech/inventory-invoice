<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceItem;

use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::all();
        return view('quotations.index', compact('quotations'));
    }

    // Show the form for creating a new quotation
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('quotations.create', compact('customers', 'products'));
    }

    // Store a newly created quotation
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'quotation_number' => 'required|unique:quotations',
            'customer_id' => 'required|exists:customers,id',
            'quotation_date' => 'required|date',
            'expiry_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Calculate total amount
        $totalAmount = collect($validatedData['items'])->sum(function ($item) {
            return $item['quantity'] * $item['unit_price'];
        });

        // Create the quotation
        $quotation = Quotation::create([
            'quotation_number' => $validatedData['quotation_number'],
            'customer_id' => $validatedData['customer_id'],
            'quotation_date' => $validatedData['quotation_date'],
            'expiry_date' => $validatedData['expiry_date'],
            'total_amount' => $totalAmount,
            'notes' => $validatedData['notes'],
        ]);

        // Create quotation items
        foreach ($validatedData['items'] as $item) {
            $quotation->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()->route('quotations.show', $quotation->id)
                         ->with('success', 'Quotation created successfully.');
    }

    // Display the specified quotation
    public function show(Quotation $quotation)
    {
        return view('quotations.show', compact('quotation'));
    }

    // Convert a quotation to an invoice
    public function convertToInvoice(Quotation $quotation)
    {
        //$quotation = Quotation::find($quotation);
        // Check if the quotation has already been converted
        if ($quotation->status === 'converted') {
            return redirect()->route('quotations.show', $quotation->id)
                             ->with('error', 'This quotation has already been converted to an invoice.');
        }

        // Create a new invoice
        $invoice = Invoice::create([
            'invoice_number' => 'INV-' . time(), // Generate a unique invoice number
            'customer_id' => $quotation->customer_id,
            'invoice_date' => now(),
            'due_date' => now()->addDays(30),
            'total_amount' => $quotation->total_amount,
            'status' => 'unpaid',
            'remaining_balance' => $quotation->total_amount,
        ]);

        // Copy quotation items to invoice items
        foreach ($quotation->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_price' => $item->total_price,
            ]);
        }

        // Update the quotation status
        $quotation->update(['status' => 'converted']);

        return redirect()->route('invoices.show', $invoice->id)
                         ->with('success', 'Quotation converted to invoice successfully.');
    }
}