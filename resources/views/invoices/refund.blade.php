@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col
<div class="container-fluid">
    <h1>Create Refund for Invoice #{{ $invoice->invoice_number }}</h1>
    <form action="{{ route('refunds.store', $invoice->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="amount_refunded" class="form-label">Amount Refunded</label>
            <input type="number" step="0.01" class="form-control" id="amount_refunded" name="amount_refunded" required>
        </div>
        <div class="mb-3">
            <label for="refund_date" class="form-label">Refund Date</label>
            <input type="date" class="form-control" id="refund_date" name="refund_date" required>
        </div>
        <div class="mb-3">
            <label for="refund_method" class="form-label">Refund Method</label>
            <select class="form-select" id="refund_method" name="refund_method" required>
                <option value="cash">Cash</option>
                <option value="credit_card">Credit Card</option>
                <option value="bank_transfer">Bank Transfer</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection