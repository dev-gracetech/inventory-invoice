@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>List Of Quotations</h3>
            <p class="text-subtitle text-muted">Manage your quotations here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="section">
        <div class="card">
            <div class="card-header">
                <div class="col-md-6">
                    <a href="{{ route('quotations.create') }}" class="btn btn-primary m-2">
                        <i class="bi bi-plus-circle"></i> Create Quotation
                    </a>
                </div>
            </div>
            <div class="card-body mt-3">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>Quotation Number</th>
                                <th>Customer</th>
                                <th>Quotation Date</th>
                                <th>Expiry Date</th>
                                <th>Total Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quotations as $quotation)
                            <tr>
                                <td>{{ $quotation->quotation_number }}</td>
                                <td>{{ $quotation->customer->name }}</td>
                                <td>{{ $quotation->quotation_date }}</td>
                                <td>{{ $quotation->expiry_date }}</td>
                                <td>${{ number_format($quotation->total_amount, 2) }}</td>
                                <td>
                                    <a href="{{ route('quotations.show', $quotation->id) }}" class="btn btn-info">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection