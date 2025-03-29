@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>{{ isset($po) ? 'Edit' : 'Create' }} Purchase Order</h3>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="section">
        <div class="card">
            <div class="card-body">
                <form action="{{ isset($po) ? route('purchase-orders.update', $po->id) : route('purchase-orders.store') }}" method="POST">
                    @csrf
                    @if(isset($po)) @method('PUT') @endif
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="supplier_id" class="form-label">Supplier</label>
                            <select class="form-select" id="supplier_id" name="supplier_id" required>
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" 
                                    @if(isset($po) && $po->supplier_id == $supplier->id) selected @endif>
                                    {{ $supplier->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="order_date" class="form-label">Order Date</label>
                            <input type="date" class="form-control" id="order_date" name="order_date" 
                                value="{{ isset($po) ? $po->order_date->format('Y-m-d') : old('order_date') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="expected_delivery_date" class="form-label">Expected Delivery</label>
                            <input type="date" class="form-control" id="expected_delivery_date" name="expected_delivery_date" 
                                value="{{ isset($po) ? $po->expected_delivery_date->format('Y-m-d') : old('expected_delivery_date') }}" required>
                        </div>
                    </div>
                    
                    <h3>Items</h3>
                    <div id="items-container">
                        @if(isset($po))
                            @foreach($po->items as $index => $item)
                            <div class="row mb-3 item-row">
                                <div class="col-md-5">
                                    <label class="form-label">Product</label>
                                    <select class="form-select product-select" name="items[{{ $index }}][product_id]" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}" 
                                            @if($item->product_id == $product->id) selected @endif
                                            data-price="{{ $product->price }}">
                                            {{ $product->name }} (Stock: {{ $product->quantity }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" class="form-control quantity" name="items[{{ $index }}][quantity]" 
                                        value="{{ $item->quantity }}" min="1" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Unit Price</label>
                                    <input type="number" step="0.01" class="form-control unit-price" 
                                        name="items[{{ $index }}][unit_price]" value="{{ $item->unit_price }}" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Total</label>
                                    <input type="text" class="form-control item-total" value="{{ $item->total_price }}" readonly>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger mt-4 remove-item">Remove</button>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="row mb-3 item-row">
                                <div class="col-md-5">
                                    <label class="form-label">Product</label>
                                    <select class="form-select product-select" name="items[0][product_id]" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                            {{ $product->name }} (Stock: {{ $product->quantity }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" class="form-control quantity" name="items[0][quantity]" min="1" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Unit Price</label>
                                    <input type="number" step="0.01" class="form-control unit-price" name="items[0][unit_price]" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Total</label>
                                    <input type="text" class="form-control item-total" readonly>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger mt-4 remove-item">Remove</button>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <button type="button" id="add-item" class="btn btn-secondary mb-3">Add Item</button>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ isset($po) ? $po->notes : old('notes') }}</textarea>
                    </div>
                    
                    @if(isset($po))
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="draft" @if($po->status == 'draft') selected @endif>Draft</option>
                            <option value="sent" @if($po->status == 'sent') selected @endif>Sent</option>
                            <option value="received" @if($po->status == 'received') selected @endif>Received</option>
                            <option value="cancelled" @if($po->status == 'cancelled') selected @endif>Cancelled</option>
                        </select>
                    </div>
                    @endif
                    
                    <button type="submit" class="btn btn-primary">Save Purchase Order</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section("custom-scripts")
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add new item row
        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const index = container.querySelectorAll('.item-row').length;
            const newRow = container.querySelector('.item-row').cloneNode(true);
            
            // Clear values
            newRow.querySelector('.product-select').selectedIndex = 0;
            newRow.querySelector('.quantity').value = '';
            newRow.querySelector('.unit-price').value = '';
            newRow.querySelector('.item-total').value = '';
            
            // Update names
            newRow.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/\[0\]/, `[${index}]`);
            });
            
            container.appendChild(newRow);
        });
        
        // Remove item row
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                if (document.querySelectorAll('.item-row').length > 1) {
                    e.target.closest('.item-row').remove();
                } else {
                    alert('At least one item is required');
                }
            }
        });
        
        // Calculate item total
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('quantity') || e.target.classList.contains('unit-price')) {
                const row = e.target.closest('.item-row');
                const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
                row.querySelector('.item-total').value = (quantity * unitPrice).toFixed(2);
            }
            
            // Auto-fill unit price when product is selected
            if (e.target.classList.contains('product-select') && e.target.value) {
                const price = e.target.options[e.target.selectedIndex].dataset.price;
                e.target.closest('.item-row').querySelector('.unit-price').value = price;
            }
        });
    });
</script>
@endsection