@extends('layouts.layout')
@section('custom-styles')
<style>
    /* Base image size */
    .product-image {
        width: 50px;
        height: 50px;
        transition: transform 0.3s ease; /* Smooth transition */
    }

    /* Enlarge image on hover */
    .product-image:hover {
        transform: scale(3); /* Enlarge by 3x */
        position: relative;
        z-index: 1000; /* Ensure the enlarged image is on top */
    }
</style>
@endsection
@section('content')
<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>List Of Products</h3>
            <p class="text-subtitle text-muted">Manage your products here.</p>
        </div>
    </div>
</div>
<div class="container-fluid">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#createProductModal">
                        <i class="bi bi-plus-circle"></i> Add Product
                    </button>
                </div>
            </div>
            <div class="card-body mt-3">
                <div class="table-responsive">
                    <table class="table datatable" id="product-table">
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Buying Price</th>
                                <th>Selling Price</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td>
                                    {{-- <button class="btn btn-info btn-sm upload-image-btn" data-stock-id="{{ $stock->id }}">Upload Image</button> --}}
                                    <a href="#" class="upload-image-btn" data-stock-id="{{ $product->id }}">
                                        <img src="{{ $product->ImageUrl }}" alt="{{ $product->name }}" width="50" height="50" class="product-image">
                                    </a>
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category }}</td>
                                <td>{{ $product->buying_price }}</td>
                                <td>{{ $product->selling_price }}</td>
                                <td>{{ $product->quantity }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm edit-product" data-bs-toggle="modal"
                                        data-bs-target="#editProductModal" data-id="{{ $product->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm delete-product" data-bs-toggle="modal"
                                        data-bs-target="#deleteProductModal{{ $product->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Create Product Modal -->
<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProductModalLabel">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        {{-- <select class="form-select" id="category" name="category" required>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select> --}}
                        <input type="text" class="form-control" id="category" name="category" required>
                    </div>
                    <div class="mb-3">
                        <label for="buying_price" class="form-label">Buying Price</label>
                        <input type="number" class="form-control" id="buying_price" name="buying_price" required>
                    </div>
                    <div class="mb-3">
                        <label for="selling_price" class="form-label">Selling Price</label>
                        <input type="number" class="form-control" id="selling_price" name="selling_price" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="editProductModal">
                <form>
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description">{{ $product->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        {{-- <select class="form-select" id="category" name="category">
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select> --}}
                        <input type="text" class="form-control" id="category" name="category" value="{{ $product->category }}">
                    </div>
                    <div class="mb-3">
                        <label for="buying_price" class="form-label">Buying Price</label>
                        <input type="number" class="form-control" id="buying_price" name="price" value="{{ $product->buying_price }}">
                    </div>
                    <div class="mb-3">
                        <label for="selling_price" class="form-label">Selling Price</label>
                        <input type="number" class="form-control" id="selling_price" name="selling_price" value="{{ $product->selling_price }}">
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" value="{{ $product->quantity }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteProductModal{{ $product->id }}" tabindex="-1" aria-labelledby="deleteProductModal{{ $product->id }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProductModal{{ $product->id }}Label">Delete Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="deleteProductModal{{ $product->id }}">
                <p>Are you sure you want to delete this product?</p>
                <form method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Product</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Image Upload Modal -->
<div class="modal fade" id="imageUploadModal" tabindex="-1" aria-labelledby="imageUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageUploadModalLabel">Upload Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="imageUploadForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="image" class="form-label">Select Image</label>
                        <input type="file" class="form-control" id="image" name="image" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload Image</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const imageUploadModal = document.getElementById('imageUploadModal');
        const imageUploadForm = document.getElementById('imageUploadForm');
        let stockId;
        // Open the modal and set the stock ID
        document.querySelectorAll('.upload-image-btn').forEach(button => {
            button.addEventListener('click', function () {
                stockId = this.getAttribute('data-stock-id');
                imageUploadModal.style.display = 'block';
                new bootstrap.Modal(imageUploadModal).show();
            });
        });
        // Handle form submission
        imageUploadForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('_token', '{{ csrf_token() }}');

            fetch(`/stocks/${stockId}/upload-image`, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Image uploaded successfully.');
                    window.location.reload();
                } else {
                    alert('Failed to upload image.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        $('#createProductModal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
        });

        $('#editProductModal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
        });

        $('#deleteProductModal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
        });

        document.querySelectorAll('.edit-product').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.getAttribute('data-id');
                fetch(`/products/${productId}/edit`)
                .then(response => response.json())
                .then(data => {
                    const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
                    const form = document.querySelector('#editProductModal form');
                    form.action = `/products/${productId}`;
                    form.querySelector('#name').value = data.name;
                    form.querySelector('#description').value = data.description;
                    form.querySelector('#category').value = data.category;
                    form.querySelector('#buying_price').value = data.buying_price;
                    form.querySelector('#selling_price').value = data.selling_price;
                    form.querySelector('#quantity').value = data.quantity;
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        //submit edit product form
        document.querySelector('#editProductModal form').addEventListener('submit', function (e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            formData.append('_method', 'PUT');
            //formData.append('_token', '{{ csrf_token() }}');
            fetch(`/products/${productId}`, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    window.location.reload();
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to update product.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });


        document.querySelectorAll('.delete-product').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.getAttribute('data-id');
                const modal = new bootstrap.Modal(document.getElementById(`deleteProductModal${productId}`));
                modal.show();
            });
        });

        //submit delete product form
        document.querySelectorAll('#deleteProductModal form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('_token', '{{ csrf_token() }}');
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        window.location.reload();
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to delete product.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });

    });
</script>
@endsection