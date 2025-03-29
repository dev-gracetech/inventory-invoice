@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Purchase Orders</h3>
            <p class="text-subtitle text-muted">Manage your purchase orders here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="section">
        <div class="card">
            <div class="card-header">
                <div class="col-md-6">
                    <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary mb-3"><i class="bi bi-plus-circle"></i> Create New PO</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>PO Number</th>
                            <th>Supplier</th>
                            <th>Order Date</th>
                            <th>Expected Delivery</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseOrders as $po)
                        <tr>
                            <td>{{ $po->po_number }}</td>
                            <td>{{ $po->supplier->name }}</td>
                            <td>{{ $po->order_date }}</td>
                            <td>{{ $po->expected_delivery_date }}</td>
                            <td>${{ number_format($po->total_amount, 2) }}</td>
                            <td>
                                <span class="badge 
                                    @if($po->status == 'draft') bg-secondary
                                    @elseif($po->status == 'sent') bg-primary
                                    @elseif($po->status == 'received') bg-success
                                    @else bg-danger
                                    @endif">
                                    {{ ucfirst($po->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('purchase-orders.show', $po->id) }}" class="btn btn-sm btn-info">View</a>
                                @if($po->status == 'draft')
                                <a href="{{ route('purchase-orders.edit', $po->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                {{ $purchaseOrders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection