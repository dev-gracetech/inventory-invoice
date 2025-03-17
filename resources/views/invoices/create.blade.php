@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Create New Invoice</h3>
            <p class="text-subtitle text-muted">Create a new invoice here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <section class="section">
        <div class="card">
            <div class="card-body mt-3">
                <form action="{{ route('invoices.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="invoice_number" class="form-label">Invoice Number</label>
                        <input type="text" class="form-control" id="invoice_number" name="invoice_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="customer" class="form-label">Customer Name</label>
                        <select class="form-select" id="customer" name="customer" required>
                            <option value="">Select a customer</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                        {{-- <input type="text" class="form-control" id="customer_name" name="customer_name" required> --}}
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="invoice_date" class="form-label">Invoice Date</label>
                                <input type="date" class="form-control" id="invoice_date" name="invoice_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="due_date" name="due_date" required>
                            </div>
                        </div>
                    </div>

                    <h3>Add Products</h3>
                    <div id="product-fields">
                        <div class="row mb-3">
                            <div class="col-md-5">
                                <label for="product_1" class="form-label">Product</label>
                                <select class="form-select product-select" name="products[]" required>
                                    <option value="">Select a product</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">{{ $product->name }} - ${{ $product->selling_price }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="quantity_1" class="form-label">Quantity</label>
                                <input type="number" class="form-control quantity-input" name="quantities[]" min="1" required>
                            </div>
                            <div class="col-md-3">
                                <label for="total_price_1" class="form-label">Total Price</label>
                                <input type="text" class="form-control total-price" readonly>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger mt-4" onclick="removeProductField(this)">Remove</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary mb-3" onclick="addProductField()">Add Product</button>

                    <div class="mb-3">
                        <label for="total_amount" class="form-label">Total Amount</label>
                        <input type="number" step="0.01" class="form-control" id="total_amount" name="total_amount" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Invoice</button>
                    <a href="{{ route('invoices.index') }}" class="btn btn-primary">Back</a>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection

@section('custom-scripts')
<script>
    // Add a new product field
    function addProductField() {
        const productFields = document.getElementById('product-fields');
        const newField = productFields.children[0].cloneNode(true);
        newField.querySelectorAll('input').forEach(input => input.value = '');
        newField.querySelector('.total-price').value = '';
        productFields.appendChild(newField);
        updateTotalAmount(); // Update total amount when a new field is added
    }

    // Remove a product field
    function removeProductField(button) {
        const row = button.closest('.row');
        if (document.getElementById('product-fields').children.length > 1) {
            row.remove();
        }
        updateTotalAmount(); // Update total amount when a new field is removed
    }

    // Calculate total price for each product and update the total amount
    function updateTotalAmount() {
        let totalAmount = 0;
        document.querySelectorAll('.row.mb-3').forEach(row => {
            const price = row.querySelector('.product-select').selectedOptions[0]?.dataset.price || 0;
            const quantity = row.querySelector('.quantity-input').value || 0;
            const totalPrice = price * quantity;
            row.querySelector('.total-price').value = totalPrice.toFixed(2);
            totalAmount += totalPrice;
        });
        document.getElementById('total_amount').value = totalAmount.toFixed(2);
    }

    // Attach event listeners for dynamic updates
    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('product-select') || e.target.classList.contains('quantity-input')) {
            updateTotalAmount();
        }
    });

    // Initialize total amount on page load
    document.addEventListener('DOMContentLoaded', function () {
        updateTotalAmount();
    });

    /* // Calculate total price for each product
    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('product-select') || e.target.classList.contains('quantity-input')) {
            const row = e.target.closest('.row');
            const price = row.querySelector('.product-select').selectedOptions[0].dataset.price;
            const quantity = row.querySelector('.quantity-input').value;
            const totalPrice = price * quantity;
            row.querySelector('.total-price').value = totalPrice.toFixed(2);
        }
    }); */
</script>
@endsection