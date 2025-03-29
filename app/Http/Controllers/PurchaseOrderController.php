<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('supplier')->latest()->paginate(10);
        return view('purchase_orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchase_orders.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'required|date|after_or_equal:order_date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);
        
        // Generate PO number
        $poNumber = 'PO-' . strtoupper(uniqid());
        
        // Calculate total amount
        $totalAmount = collect($validated['items'])->sum(function($item) {
            return $item['quantity'] * $item['unit_price'];
        });
        
        // Create PO
        $po = PurchaseOrder::create([
            'po_number' => $poNumber,
            'supplier_id' => $validated['supplier_id'],
            'order_date' => $validated['order_date'],
            'expected_delivery_date' => $validated['expected_delivery_date'],
            'total_amount' => $totalAmount,
            'notes' => $validated['notes'] ?? null,
            'status' => 'draft'
        ]);
        
        // Add items
        foreach ($validated['items'] as $item) {
            $po->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price']
            ]);
        }
        
        return redirect()->route('purchase-orders.show', $po->id)
                         ->with('success', 'Purchase order created successfully!');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        return view('purchase_orders.show', [
            'po' => $purchaseOrder->load(['supplier', 'items.product'])
        ]);
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchase_orders.edit', [
            'po' => $purchaseOrder,
            'suppliers' => $suppliers,
            'products' => $products
        ]);
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'required|date|after_or_equal:order_date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,sent,received,cancelled'
        ]);
        
        // Calculate total amount
        $totalAmount = collect($validated['items'])->sum(function($item) {
            return $item['quantity'] * $item['unit_price'];
        });
        
        // Update PO
        $purchaseOrder->update([
            'supplier_id' => $validated['supplier_id'],
            'order_date' => $validated['order_date'],
            'expected_delivery_date' => $validated['expected_delivery_date'],
            'total_amount' => $totalAmount,
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status']
        ]);
        
        // Sync items
        $purchaseOrder->items()->delete();
        foreach ($validated['items'] as $item) {
            $purchaseOrder->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price']
            ]);
        }
        
        return redirect()->route('purchase-orders.show', $purchaseOrder->id)
                         ->with('success', 'Purchase order updated successfully!');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->items()->delete();
        $purchaseOrder->delete();
        return redirect()->route('purchase-orders.index')
                         ->with('success', 'Purchase order deleted successfully!');
    }
    
    public function send(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->update(['status' => 'sent']);
        return back()->with('success', 'Purchase order sent to supplier!');
    }
    
    public function receive(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->update(['status' => 'received']);
        
        // Update inventory
        foreach ($purchaseOrder->items as $item) {
            $product = $item->product;
            $product->increment('quantity', $item->quantity);
            $product->save();
        }
        
        return back()->with('success', 'Purchase order marked as received! Inventory updated.');
    }
}
