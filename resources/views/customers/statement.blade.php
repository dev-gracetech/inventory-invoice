@extends('layouts.layout')

@section('content')
<div class="page-title">
    <h3>Customer Statement</h3>
    <p class="text-subtitle text-muted">View customer statement</p>
</div>
<div class="container-fluid">
    <div class="section">
        <div class="card">
            <div class="card-body">
                <h1>Customer Statement: {{ $customer->name }}</h1>
                <p><strong>Email:</strong> {{ $customer->email }}</p>
                <p><strong>Phone:</strong> {{ $customer->phone }}</p>
                <p><strong>Address:</strong> {{ $customer->address }}</p>

                <h3>Summary</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Total Invoiced</th>
                            <th>Total Paid</th>
                            <th>Total Refunded</th>
                            <th>Outstanding Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>${{ number_format($totalInvoiced, 2) }}</td>
                            <td>${{ number_format($totalPaid, 2) }}</td>
                            <td>${{ number_format($totalRefunded, 2) }}</td>
                            <td>${{ number_format($outstandingBalance, 2) }}</td>
                        </tr>
                    </tbody>
                </table>

                <h3>Transaction Details</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_date }}</td>
                            <td>Invoice</td>
                            <td>Invoice #{{ $invoice->invoice_number }}</td>
                            <td>${{ number_format($invoice->total_amount, 2) }}</td>
                        </tr>
                        @foreach($invoice->receipts as $receipt)
                        <tr>
                            <td>{{ $receipt->payment_date }}</td>
                            <td>Receipt</td>
                            <td>Payment for Invoice #{{ $invoice->invoice_number }}</td>
                            <td>-${{ number_format($receipt->amount_paid, 2) }}</td>
                        </tr>
                        @endforeach
                        @foreach($invoice->refunds as $refund)
                        <tr>
                            <td>{{ $refund->refund_date }}</td>
                            <td>Refund</td>
                            <td>Refund for Invoice #{{ $invoice->invoice_number }}</td>
                            <td>${{ number_format($refund->amount_refunded, 2) }}</td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            <div class="d-flex justify-content-end">
                <a href="{{ route('customers.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection