@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Dashboard</h3>
            <p class="text-subtitle text-muted">Manage your dashboard here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="section">
        <div class="card">
            <div class="card-body">
                <!-- Key Metrics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Invoices</h5>
                                <p class="card-text">{{ $totalInvoices }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Revenue</h5>
                                <p class="card-text">${{ number_format($totalRevenue, 2) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Pending Invoices</h5>
                                <p class="card-text">{{ $pendingInvoices }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Products</h5>
                                <p class="card-text">{{ $totalProducts }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total POs</h5>
                                <p class="card-text">{{ $totalPOs }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Pending POs</h5>
                                <p class="card-text">{{ $pendingPOs }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total PO Amount</h5>
                                <p class="card-text">${{ number_format($totalPOAmount, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Recent Invoices</h5>
                            </div>
                            <div class="card-body mt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Invoice Number</th>
                                            <th>Customer</th>
                                            <th>Total Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentInvoices as $invoice)
                                        <tr>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>{{ $invoice->customer->name }}</td>
                                            <td>${{ number_format($invoice->total_amount, 2) }}</td>
                                            <td>{{ ucfirst($invoice->status) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Recent Quotations</h5>
                            </div>
                            <div class="card-body mt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Quotation Number</th>
                                            <th>Customer</th>
                                            <th>Total Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentQuotations as $quotation)
                                        <tr>
                                            <td>{{ $quotation->quotation_number }}</td>
                                            <td>{{ $quotation->customer->name }}</td>
                                            <td>${{ number_format($quotation->total_amount, 2) }}</td>
                                            <td>{{ ucfirst($quotation->status) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Quick Links</h5>
                            </div>
                            <div class="card-body mt-3">
                                <a href="{{ route('invoices.create') }}" class="btn btn-primary">Create Invoice</a>
                                <a href="{{ route('quotations.create') }}" class="btn btn-success">Create Quotation</a>
                                <a href="{{ route('invoices.index') }}" class="btn btn-info">View All Invoices</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection