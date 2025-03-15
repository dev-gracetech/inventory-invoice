@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Invoice List</h3>
            <p class="text-subtitle text-muted">Manage your invoices here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="section">
        <div class="card">
            <div class="card-header">
                <div class="col-md-6">
                    <a href="{{ route('invoices.create') }}" class="btn btn-primary m-2">
                        <i class="bi bi-plus-circle"></i> Create Invoice
                    </a>
                </div>
            </div>
            <div class="card-body mt-3">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>Invoice Number</th>
                                <th>Customer Name</th>
                                <th>Invoice Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->customer->name }}</td>
                                <td>{{ $invoice->invoice_date }}</td>
                                <td>${{ number_format($invoice->total_amount, 2) }}</td>
                                <td>
                                    @if($invoice->status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                    @else
                                    <span class="badge bg-danger">Unpaid</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewInvoiceModal{{ $invoice->id }}">View</button> --}}
                                    <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info btn-sm">View</a>
                                    @if($invoice->status == 'unpaid')
                                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>

                            <!-- View Invoice Modal -->
                            <div class="modal fade" id="viewInvoiceModal{{ $invoice->id }}" tabindex="-1" aria-labelledby="viewInvoiceModalLabel{{ $invoice->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewInvoiceModalLabel{{ $invoice->id }}">Invoice Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body bg-dark">
                                            <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
                                            <p><strong>Customer Name:</strong> {{ $invoice->customer_name }}</p>
                                            <p><strong>Invoice Date:</strong> {{ $invoice->invoice_date }}</p>
                                            <p><strong>Total Amount:</strong> ${{ number_format($invoice->total_amount, 2) }}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection