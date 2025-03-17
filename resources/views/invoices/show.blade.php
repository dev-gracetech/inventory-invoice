@extends('layouts.layout')

@section('content')
<div class="page-title">
    @if(session('success'))
    <div class="d-flex justify-content-end">
        <div class="col-2 alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    @if(session('error'))
        <div class="col-2 alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
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
            <div class="card-body">
                <div class="row">
                    <p><h2><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</h2></p>
                    <p><strong>Customer Name:</strong> {{ $invoice->customer->name }}</p>
                    <p><strong>Invoice Date:</strong> {{ $invoice->invoice_date }}</p>
                    <p><strong>Due Date:</strong> {{ $invoice->due_date }}</p>
                    <p><strong>Total Amount:</strong> ${{ number_format($invoice->total_amount, 2) }}</p>
                    <p><strong>Amount Paid:</strong> ${{ number_format($invoice->receipts->sum('amount_paid'), 2) }}</p>
                    <p><strong>Amount Refunded:</strong> ${{ number_format($invoice->refunds->sum('amount_refunded'), 2) }}</p>
                    <p><strong>Amount Due:</strong> ${{ number_format($invoice->remaining_balance, 2) }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
                </div>

                <div class="row">
                    <div class="card">
                        <div class="card-body">
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
                        </div>
                    </div>
                </div>

                
                <div class="row">
                    <div class="card">
                        <div class="card-body">
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
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <h3>Refunds</h3>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Refund Date</th>
                                        <th>Amount Refunded</th>
                                        <th>Refund Method</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoice->refunds as $refund)
                                    <tr>
                                        <td>{{ $refund->refund_date }}</td>
                                        <td>${{ number_format($refund->amount_refunded, 2) }}</td>
                                        <td>{{ $refund->refund_method }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if($invoice->status !== 'paid')
                    <a href="{{ route('receipts.create-receipt', $invoice->id) }}" class="btn btn-success">Add Receipt</a>
                    <a href="{{ route('refunds.create-refund', $invoice->id) }}" class="btn btn-warning">Add Refund</a>
                @else
                    <div class="alert alert-info">
                        This invoice is fully paid. No further receipts can be created.
                    </div>
                @endif
                <a href="{{ route('invoices.index') }}" class="btn btn-primary">Back</a>
                
            </div>
        </div>
    </div>
</div>
@endsection