@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Purchase Order Details: {{ $po->po_number }}</h3>
            <p class="text-subtitle text-muted">View purchase order details here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                @if($po->status == 'draft')
                    <a href="{{ route('purchase-orders.edit', $po->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('purchase-orders.send', $po->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">Send to Supplier</button>
                    </form>
                @elseif($po->status == 'sent')
                    <form action="{{ route('purchase-orders.receive', $po->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Mark as Received</button>
                    </form>
                @endif
            </div>
        </div>
    
    
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Supplier Details</h5>
                        <p><strong>Name:</strong> {{ $po->supplier->name }}</p>
                        <p><strong>Email:</strong> {{ $po->supplier->email }}</p>
                        <p><strong>Phone:</strong> {{ $po->supplier->phone }}</p>
                        <p><strong>Address:</strong> {{ $po->supplier->address }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>PO Details</h5>
                        <p><strong>Order Date:</strong> {{ $po->order_date }}</p>
                        <p><strong>Expected Delivery:</strong> {{ $po->expected_delivery_date }}</p>
                        <p><strong>Status:</strong> 
                            <span class="badge 
                                @if($po->status == 'draft') bg-secondary
                                @elseif($po->status == 'sent') bg-primary
                                @elseif($po->status == 'received') bg-success
                                @else bg-danger
                                @endif">
                                {{ ucfirst($po->status) }}
                            </span>
                        </p>
                        <p><strong>Total Amount:</strong> ${{ number_format($po->total_amount, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5>Items</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($po->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->unit_price, 2) }}</td>
                            <td>${{ number_format($item->total_price, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td><strong>${{ number_format($po->total_amount, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($po->notes)
        <div class="card mt-3">
            <div class="card-header">
                <h5>Notes</h5>
            </div>
            <div class="card-body">
                {{ $po->notes }}
            </div>
        </div>
        @endif
        
        <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary mt-3">Back to List</a>
    </div>
</div>
@endsection