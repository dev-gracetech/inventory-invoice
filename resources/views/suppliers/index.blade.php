@extends('layouts.layout')

@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>List Of Suppliers</h3>
            <p class="text-subtitle text-muted">Manage your suppliers here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#createSupplierModal">
                        <i class="bi bi-plus-circle"></i> Add Supplier
                    </button>
                </div>
            </div>
            <div class="card-body mt-3">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->id }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->email }}</td>
                                <td>{{ $supplier->phone }}</td>
                                <td>{{ $supplier->address }}</td>
                                <td>
                                    {{-- <a href="{{ route('customers.statement', $customer->id) }}" class="btn btn-info btn-sm" title="View Statement">
                                        <i class="bi bi-file-text"></i>
                                    </a> --}}
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editSupplierModal{{ $supplier->id }}"
                                        title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteSupplierModal{{ $supplier->id }}"
                                        title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Edit Customer Modal -->
                            <div class="modal fade" id="editSupplierModal{{ $supplier->id }}" tabindex="-1" aria-labelledby="editSupplierModalLabel{{ $supplier->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editSupplierModalLabel{{ $supplier->id }}">Edit Supplier</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Name</label>
                                                    <input type="text" class="form-control" id="name" name="name" value="{{ $supplier->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email" value="{{ $supplier->email }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="phone" class="form-label">Phone</label>
                                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ $supplier->phone }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="address" class="form-label">Address</label>
                                                    <textarea class="form-control" id="address" name="address">{{ $supplier->address }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Customer Modal -->
                            <div class="modal fade" id="deleteSupplierModal{{ $supplier->id }}" tabindex="-1" aria-labelledby="deleteSupplierModalLabel{{ $supplier->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteSupplierModalLabel{{ $supplier->id }}">Delete Supplier</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-body">
                                                Are you sure you want to delete this supplier?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Create Customer Modal -->
<div class="modal fade" id="createSupplierModal" tabindex="-1" aria-labelledby="createSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSupplierModalLabel">Add New Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection