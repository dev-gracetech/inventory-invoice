@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Create New Quotation</h3>
            <p class="text-subtitle text-muted">Create a new quotation here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="section">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('quotations.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="quotation_number" class="form-label">Quotation Number</label>
                        <input type="text" class="form-control" id="quotation_number" name="quotation_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Customer</label>
                        <select class="form-select" id="customer_id" name="customer_id" required>
                            <option value="">Select a customer</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quotation_date" class="form-label">Quotation Date</label>
                        <input type="date" class="form-control" id="quotation_date" name="quotation_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="date" class="form-control" id="expiry_date" name="expiry_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>

                    <h3>Quotation Items</h3>
                    <div id="item-fields">
                        <div class="row mb-3">
                            <div class="col-md-5">
                                <label for="product_id_1" class="form-label">Product</label>
                                <select class="form-select product-select" name="items[0][product_id]" required>
                                    <option value="">Select a product</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">{{ $product->name }} - ${{ $product->selling_price }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="quantity_1" class="form-label">Quantity</label>
                                <input type="number" class="form-control quantity-input" name="items[0][quantity]" min="1" required>
                            </div>
                            <div class="col-md-3">
                                <label for="unit_price_1" class="form-label">Unit Price</label>
                                <input type="number" step="0.01" class="form-control unit-price-input" name="items[0][unit_price]" required>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger mt-4" onclick="removeItemField(this)">Remove</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary mb-3" onclick="addItemField()">Add Item</button>
                    <div class="mb-3">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('quotations.index') }}" class="btn btn-primary">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom-scripts')
<script>
    // Add a new item field
    function addItemField() {
        const itemFields = document.getElementById('item-fields');
        const newField = itemFields.children[0].cloneNode(true);
        const index = itemFields.children.length;
        newField.innerHTML = newField.innerHTML.replace(/\[0\]/g, `[${index}]`);
        newField.querySelectorAll('input').forEach(input => input.value = '');
        itemFields.appendChild(newField);
    }

    // Remove an item field
    function removeItemField(button) {
        const row = button.closest('.row');
        if (document.getElementById('item-fields').children.length > 1) {
            row.remove();
        }
    }
</script>
@endsection