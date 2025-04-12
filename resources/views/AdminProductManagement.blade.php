<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dressify - Admin Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/app.css">
</head>

<body>
    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    
        <div class="container py-4">
            <div class="row">
                <!-- Sidebar Navigation -->
                <div class="col-md-3 mb-3">
                    <div class="list-group sidebar">
                        <a href="AdminProductManagement.html"
                            class="list-group-item list-group-item-action active">Products</a>
                        <a href="AdminOrderManagement.html" class="list-group-item list-group-item-action">Orders</a>
                    </div>
                </div>
    <main>
                <!-- Main Content -->
                <div class="col-md-9">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h1 class="mb-0 h5">Product Management</h1>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                <i class="bi bi-plus"></i> Add New Product
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Color</th>
                                            <th>Price</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="card-img-container">
                                                    <img src="images/blueGant.png" alt="Basic Tee">
                                                </div>
                                            </td>
                                            <td>Basic Tee</td>
                                            <td>Blue</td>
                                            <td>$32</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal"
                                                    data-bs-target="#editProductModal">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteProductModal">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- More products would go here -->
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
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addProductForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" class="form-control" name="productName" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Price ($)</label>
                                    <input type="number" class="form-control" name="price" step="0.01" min="0" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Color</label>
                                    <select class="form-select" name="color" required>
                                        <option value="">Select Color</option>
                                        <option value="White">White</option>
                                        <option value="Black">Black</option>
                                        <option value="Blue">Blue</option>
                                        <option value="Green">Green</option>
                                        <option value="Purple">Purple</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="Tees">Tees</option>
                                        <option value="Crewnecks">Crewnecks</option>
                                        <option value="Sweatshirts">Sweatshirts</option>
                                        <option value="Pants">Pants & Shorts</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="3" name="description" required></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Product Image</label>
                                    <input type="file" class="form-control" name="productImage" accept="image/*" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Available Sizes</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="sizes" value="xs" id="add-size-xs">
                                            <label class="form-check-label" for="add-size-xs">XS</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="sizes" value="s" id="add-size-s">
                                            <label class="form-check-label" for="add-size-s">S</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="sizes" value="m" id="add-size-m">
                                            <label class="form-check-label" for="add-size-m">M</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="sizes" value="l" id="add-size-l">
                                            <label class="form-check-label" for="add-size-l">L</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="sizes" value="xl" id="add-size-xl">
                                            <label class="form-check-label" for="add-size-xl">XL</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="sizes" value="2xl" id="add-size-2xl">
                                            <label class="form-check-label" for="add-size-2xl">2XL</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Stock Quantity</label>
                                    <input type="number" class="form-control" name="stockQuantity" min="0" required>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" form="addProductForm" class="btn btn-primary">Add Product</button>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Edit Product Modal -->
        <div class="modal fade" id="editProductModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Product: Basic Tee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editProductForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" class="form-control" name="productName" value="Basic Tee" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Price ($)</label>
                                    <input type="number" class="form-control" name="price" step="0.01" min="0" value="32" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Color</label>
                                    <select class="form-select" name="color" required>
                                        <option value="">Select Color</option>
                                        <option value="White">White</option>
                                        <option value="Black">Black</option>
                                        <option value="Blue" selected>Blue</option>
                                        <option value="Green">Green</option>
                                        <option value="Purple">Purple</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="Tees" selected>Tees</option>
                                        <option value="Crewnecks">Crewnecks</option>
                                        <option value="Sweatshirts">Sweatshirts</option>
                                        <option value="Pants">Pants & Shorts</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="3" name="description" required>The Basic Tee is an everyday essential. It's made of soft, comfortable cotton and has a classic crew neck design.</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Current Image</label>
                                    <div class="mb-2">
                                        <img src="images/blueGant.png" alt="Current Product Image" class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                    <label class="form-label">Replace Image (optional)</label>
                                    <input type="file" class="form-control" name="productImage" accept="image/*">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Available Sizes</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="sizes" value="xs" id="edit-size-xs">
                                            <label class="form-check-label" for="edit-size-xs">XS</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="sizes" value="s" id="edit-size-s" checked>
                                            <label class="form-check-label" for="edit-size-s">S</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="sizes" value="m" id="edit-size-m" checked>
                                            <label class="form-check-label" for="edit-size-m">M</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="sizes" value="l" id="edit-size-l" checked>
                                            <label class="form-check-label" for="edit-size-l">L</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="sizes" value="xl" id="edit-size-xl" checked>
                                            <label class="form-check-label" for="edit-size-xl">XL</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="sizes" value="2xl" id="edit-size-2xl">
                                            <label class="form-check-label" for="edit-size-2xl">2XL</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Stock Quantity</label>
                                    <input type="number" class="form-control" name="stockQuantity" min="0" value="45" required>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" form="editProductForm" class="btn btn-primary">Update Product</button>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Delete Product Modal -->
        <div class="modal fade" id="deleteProductModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Product Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the product "Basic Tee"? This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger">Delete Product</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    @include('partials/footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>