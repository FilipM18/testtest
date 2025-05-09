<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dressify - Admin Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('partials.header')

    <main>
        <div class="container py-4">
            <div class="row">
                <!-- Sidebar Navigation -->
                <div class="col-md-3 mb-3">
                    <div class="list-group sidebar">
                        <a href="{{ route('admin.products') }}" class="list-group-item list-group-item-action active">Products</a>
                        <a href="{{ route('admin.orders') }}" class="list-group-item list-group-item-action">Orders</a>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-md-9">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h1 class="mb-0 h5">Product Management</h1>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                <i class="bi bi-plus"></i> Add New Product
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Brand</th>
                                            <th>Category</th>
                                            <th>Gender</th>
                                            <th>Price</th>
                                            <th>Active</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>
                                                @php
                                                    $img = is_array($product->image_url) ? $product->image_url[0] : (is_string($product->image_url) ? json_decode($product->image_url, true)[0] ?? null : null);
                                                @endphp
                                                @if($img)
                                                    <img src="{{ asset($img) }}" alt="{{ $product->name }}" style="width:60px;">
                                                @else
                                                    <span>No image</span>
                                                @endif
                                            </td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                            <td>{{ $product->type }}</td>
                                            <td>{{ $product->gender }}</td>
                                            <td>${{ number_format($product->price, 2) }}</td>
                                            <td>
                                                @if($product->active)
                                                    <span class="badge bg-success">Yes</span>
                                                @else
                                                    <span class="badge bg-secondary">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary me-2 btn-edit-product"
                                                    data-id="{{ $product->product_id }}" data-bs-toggle="modal"
                                                    data-bs-target="#editProductModal">
                                                    <i class="bi bi-pencil"></i>
                                                </button>

                                                <button class="btn btn-sm btn-outline-danger btn-delete-product"
                                                    data-id="{{ $product->product_id }}" data-name="{{ $product->name }}"
                                                    data-bs-toggle="modal" data-bs-target="#deleteProductModal">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if($products->isEmpty())
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">No products found.</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Product Modal -->
        <div class="modal fade" id="addProductModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="addProductForm" method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Price ($)</label>
                                    <input type="number" class="form-control" name="price" step="0.01" min="0" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Brand</label>
                                    <select class="form-select" name="brand_id" required>
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->brand_id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Gender</label>
                                    <select class="form-select" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="unisex">Unisex</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Category</label>
                                    <input type="text" class="form-control" name="type" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="3" name="description" required></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Product Images</label>
                                    <input type="file" class="form-control" name="images[]" accept="image/*" multiple required>
                                </div>
                                
                                <!-- Product Variants Section -->
                                <div class="col-12 mt-4">
                                    <h5>Product Variants</h5>
                                    <p class="text-muted small">Add size and color variants for this product</p>
                                    
                                    <div id="variants-container">
                                        <div class="variant-row row mb-3 align-items-center">
                                            <div class="col-md-4">
                                                <label class="form-label">Color</label>
                                                <input type="text" class="form-control" name="variants[0][color]" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Size</label>
                                                <select class="form-select" name="variants[0][size]" required>
                                                    <option value="">Select Size</option>
                                                    <option value="xs">XS</option>
                                                    <option value="s">S</option>
                                                    <option value="m">M</option>
                                                    <option value="l">L</option>
                                                    <option value="xl">XL</option>
                                                    <option value="2xl">2XL</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Stock Quantity</label>
                                                <input type="number" class="form-control" name="variants[0][stock]" min="0" value="10" required>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-danger remove-variant mt-4" style="display:none;">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button type="button" class="btn btn-outline-secondary mt-2" id="add-variant">
                                        <i class="bi bi-plus"></i> Add Another Variant
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Product Modal -->
        <div class="modal fade" id="editProductModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="editProductForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProductModalTitle">Edit Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="product_id" id="editProductId">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" class="form-control" name="name" id="editProductName" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Price ($)</label>
                                    <input type="number" class="form-control" name="price" id="editProductPrice" step="0.01" min="0" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Brand</label>
                                    <select class="form-select" name="brand_id" id="editProductBrand" required>
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->brand_id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Gender</label>
                                    <select class="form-select" name="gender" id="editProductGender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="unisex">Unisex</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Category</label>
                                    <input type="text" class="form-control" name="type" id="editProductType" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="3" name="description" id="editProductDescription" required></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Current Images</label>
                                    <div id="editProductImages" class="mb-2"></div>
                                    <label class="form-label">Add/Replace Images</label>
                                    <input type="file" class="form-control" name="images[]" accept="image/*" multiple>
                                </div>
                                
                                <div class="col-12 mt-4">
                                    <h5>Product Variants</h5>
                                    <p class="text-muted small">Edit existing variants or add new ones</p>
                                    
                                    <div id="edit-variants-container">
                                    </div>
                                    
                                    <button type="button" class="btn btn-outline-secondary mt-2" id="edit-add-variant">
                                        <i class="bi bi-plus"></i> Add Another Variant
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Delete Product Modal -->
        <div class="modal fade" id="deleteProductModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="deleteProductForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteProductModalTitle">Confirm Product Deletion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p id="deleteProductModalBody">Are you sure you want to delete this product? This action cannot be undone.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    @include('partials.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
    // CSRF setup for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#add-variant').click(function() {
        let variantIndex = $('.variant-row').length;
        const newRow = `
            <div class="variant-row row mb-3 align-items-center">
                <div class="col-md-4">
                    <label class="form-label">Color</label>
                    <input type="text" class="form-control" name="variants[${variantIndex}][color]" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Size</label>
                    <select class="form-select" name="variants[${variantIndex}][size]" required>
                        <option value="">Select Size</option>
                        <option value="XS">XS</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                        <option value="2XL">2XL</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Stock Quantity</label>
                    <input type="number" class="form-control" name="variants[${variantIndex}][stock]" min="0" value="10" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger remove-variant mt-4">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
        $('#variants-container').append(newRow);
        
        if ($('.variant-row').length > 1) {
            $('.remove-variant').show();
        }
    });

    $(document).on('click', '.remove-variant', function() {
        $(this).closest('.variant-row').remove();
        
        if ($('.variant-row').length <= 1) {
            $('.remove-variant').hide();
        }
    });


    // Edit variant management
    let editVariantIndex = 0;

    $('.btn-edit-product').on('click', function() {
        $('#editProductModal .modal-body').prepend('<div class="text-center py-4" id="editProductLoading">Loading...</div>');
        const productId = $(this).data('id');
        
        // Reset the variants container
        $('#edit-variants-container').empty();
        editVariantIndex = 0;
        
        $.get(`/admin/products/${productId}/data`)
            .done(function(data) {
                $('#editProductLoading').remove();
                $('#editProductId').val(data.product_id);
                $('#editProductName').val(data.name);
                $('#editProductPrice').val(data.price);
                $('#editProductBrand').val(data.brand_id);
                $('#editProductGender').val(data.gender);
                $('#editProductType').val(data.type);
                $('#editProductDescription').val(data.description);
                $('#editProductModalTitle').text('Edit Product: ' + data.name);

                // Show current images
                let imagesHtml = '';
                if(data.images && data.images.length > 0) {
                    data.images.forEach((img, idx) => {
                        imagesHtml += `<div class="d-inline-block me-2 mb-2 position-relative">
                            <img src="/${img}" alt="Product Image" class="img-thumbnail" style="max-height: 80px;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 delete-image-btn" data-product-id="${data.product_id}" data-image-index="${idx}" style="z-index:2;"><i class="bi bi-x"></i></button>
                        </div>`;
                    });
                }
                $('#editProductImages').html(imagesHtml);
                
                // Load variants
                if (data.variants && data.variants.length > 0) {
                    data.variants.forEach((variant, index) => {
                        addEditVariantRow(variant, index);
                    });
                    
                    // Show remove buttons if more than one variant
                    if (data.variants.length > 1) {
                        $('.edit-remove-variant').show();
                    } else {
                        $('.edit-remove-variant').hide();
                    }
                } else {
                    // Add one empty variant row if none exist
                    addEditVariantRow();
                }
                
                $('#editProductForm').attr('action', `/admin/products/${productId}`);
            })
            .fail(function() {
                $('#editProductLoading').remove();
                $('#editProductModal .modal-body').html('<div class="alert alert-danger">Failed to load product data.</div>');
            });
    });

    // Add variant row for edit form
    function addEditVariantRow(variant = null, index = null) {
        if (index === null) {
            index = editVariantIndex++;
        } else {
            editVariantIndex = Math.max(editVariantIndex, index + 1);
        }
        
        const variantId = variant ? variant.variant_id : '';
        const color = variant ? variant.color : '';
        const size = variant ? variant.size : '';
        const stock = variant ? variant.stock_quantity : 10;
        
        const newRow = `
            <div class="variant-row row mb-3 align-items-center">
                <input type="hidden" name="edit_variants[${index}][variant_id]" value="${variantId}">
                <div class="col-md-4">
                    <label class="form-label">Color</label>
                    <input type="text" class="form-control" name="edit_variants[${index}][color]" value="${color}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Size</label>
                    <select class="form-select" name="edit_variants[${index}][size]" required>
                        <option value="">Select Size</option>
                        <option value="XS" ${size === 'xs' ? 'selected' : ''}>XS</option>
                        <option value="S" ${size === 's' ? 'selected' : ''}>S</option>
                        <option value="M" ${size === 'm' ? 'selected' : ''}>M</option>
                        <option value="L" ${size === 'l' ? 'selected' : ''}>L</option>
                        <option value="XL" ${size === 'xl' ? 'selected' : ''}>XL</option>
                        <option value="2XL" ${size === '2xl' ? 'selected' : ''}>2XL</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Stock Quantity</label>
                    <input type="number" class="form-control" name="edit_variants[${index}][stock]" min="0" value="${stock}" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger edit-remove-variant mt-4" ${$('.variant-row').length <= 0 ? 'style="display:none;"' : ''}>
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
        $('#edit-variants-container').append(newRow);
    }

    // Add new variant row in edit form
    $(document).on('click', '#edit-add-variant', function() {
        addEditVariantRow();
        
        // Show all remove buttons when we have more than one variant
        if ($('#edit-variants-container .variant-row').length > 1) {
            $('.edit-remove-variant').show();
        }
    });

    // Remove variant row in edit form
    $(document).on('click', '.edit-remove-variant', function() {
        $(this).closest('.variant-row').remove();
        
        // Hide remove buttons if only one variant remains
        if ($('#edit-variants-container .variant-row').length <= 1) {
            $('.edit-remove-variant').hide();
        }
    });

    // Delete image from product (AJAX)
    $(document).on('click', '.delete-image-btn', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        const imageIndex = $(this).data('image-index');
        const btn = $(this);
        if(confirm('Delete this image?')) {
            $.post(`/admin/products/${productId}/images/${imageIndex}/ajax`, {_method: 'POST'}, function(resp) {
                if(resp.success) {
                    btn.closest('.d-inline-block').remove();
                } else {
                    alert('Could not delete image.');
                }
            });
        }
    });

    // Delete Product Modal population
    $('.btn-delete-product').on('click', function() {
        const productId = $(this).data('id');
        const productName = $(this).data('name');
        $('#deleteProductModalTitle').text('Delete Product: ' + productName);
        $('#deleteProductModalBody').text(`Are you sure you want to delete the product "${productName}"? This action cannot be undone.`);
        $('#deleteProductForm').attr('action', `/admin/products/${productId}`);
    });
    </script>
</body>
</html>
