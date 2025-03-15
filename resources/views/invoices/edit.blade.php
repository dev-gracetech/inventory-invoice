@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Edit Invoice</h3>
            <p class="text-subtitle text-muted">Edit invoice {{ $invoice->invoice_number }}</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="section">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="invoice_number" class="form-label">Invoice Number</label>
                        <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="{{ $invoice->invoice_number }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="customer" class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="customer" name="customer" value="{{ $invoice->customer->name }}" readonly>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="invoice_date" class="form-label">Invoice Date</label>
                                <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="{{ $invoice->invoice_date }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Invoice Due Date</label>
                                <input type="date" class="form-control" id="due_date" name="due_date" value="{{ $invoice->due_date }}" required>
                            </div>
                        </div>
                    </div>

                    <h3>Invoice Items</h3>
                    <div id="product-fields">
                        @foreach($invoice->products as $index => $product)
                        <div class="row mb-3">
                            <div class="col-md-5">
                                <select class="form-select product-select" name="products[]" required>
                                    <option value="">Select a product</option>
                                    @foreach($products as $p)
                                    <option value="{{ $p->id }}" data-price="{{ $p->selling_price }}" {{ $p->id == $product->id ? 'selected' : '' }}>
                                        {{ $p->name }} - ${{ $p->selling_price }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control quantity-input" name="quantities[]" value="{{ $product->pivot->quantity }}" min="1" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control total-price" readonly>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger mt-4" onclick="removeProductField(this)">Remove</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-secondary mb-3" onclick="addProductField()">Add Product</button>

                    <!-- Total Amount Field -->
                    <div class="mb-3">
                        <label for="total_amount" class="form-label">Total Amount</label>
                        <input type="text" class="form-control" id="total_amount" name="total_amount" value="{{ $invoice->total_amount }}" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
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
        updateTotalAmount(); // Update total amount when a field is removed
    }

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

    document.addEventListener('input', function (e) {
        if (e.target.classList.contains('product-select') || e.target.classList.contains('quantity-input')) {
            updateTotalAmount(); // Recalculate total amount when product or quantity changes
        }
    });

    // Initialize total amount on page load
    document.addEventListener('DOMContentLoaded', function () {
        updateTotalAmount();
    });
</script>
@endsection