@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Invoice Details</h3>
            <p class="text-subtitle text-muted">View invoice details here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="section">
        <div class="card">
            <div class="card-body mt-3">
                <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Customer Name:</strong> {{ $invoice->customer->name }}</p>
                <p><strong>Invoice Date:</strong> {{ $invoice->invoice_date }}</p>
                <p><strong>Due Date:</strong> {{ $invoice->due_date }}</p>
                <p><strong>Total Amount:</strong> ${{ number_format($invoice->total_amount, 2) }}</p>
                <p><strong>Amount Paid:</strong> ${{ number_format($invoice->receipts->sum('amount_paid'), 2) }}</p>
                <p><strong>Amount Due:</strong> ${{ number_format($invoice->remaining_balance, 2) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>

                <h3>Invoice Items</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->pivot->quantity }}</td>
                            <td>${{ number_format($product->pivot->unit_price, 2) }}</td>
                            <td>${{ number_format($product->pivot->total_price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <h3>Receipts</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Payment Date</th>
                            <th>Amount Paid</th>
                            <th>Payment Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->receipts as $receipt)
                        <tr>
                            <td>{{ $receipt->payment_date }}</td>
                            <td>${{ number_format($receipt->amount_paid, 2) }}</td>
                            <td>{{ $receipt->payment_method }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($invoice->status !== 'paid')
                    <a href="{{ route('receipts.create-receipt', $invoice->id) }}" class="btn btn-success">Add Receipt</a>
                @else
                    <div class="alert alert-info">
                        This invoice is fully paid. No further receipts can be created.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection