@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Quotation Details</h3>
            <p class="text-subtitle text-muted">View quotation details here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="section">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <p><strong>Quotation Number:</strong> {{ $quotation->quotation_number }}</p>
                    <p><strong>Customer:</strong> {{ $quotation->customer->name }}</p>
                    <p><strong>Quotation Date:</strong> {{ $quotation->quotation_date }}</p>
                    <p><strong>Expiry Date:</strong> {{ $quotation->expiry_date }}</p>
                    <p><strong>Total Amount:</strong> ${{ number_format($quotation->total_amount, 2) }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($quotation->status) }}</p>
                    <p>Quotation ID: {{ $quotation->id }}</p>
                </div>
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <h3>Quotation Items</h3>
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
                                    @foreach($quotation->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->unit_price, 2) }}</td>
                                        <td>${{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if($quotation->status !== 'converted')
                    <form action="{{ route('quotations.convert-to-invoice', $quotation->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Convert to Invoice</button>
                        <a href="{{ route('quotations.index') }}" class="btn btn-primary">Back</a>
                    </form>
                @else
                    <div class="alert alert-info">
                        This quotation has already been converted to an invoice.
                    </div>
                    <a href="{{ route('quotations.index') }}" class="btn btn-primary">Back</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection