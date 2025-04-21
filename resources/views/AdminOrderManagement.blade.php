<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dressify - Admin Orders Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/app.css">
</head>

<body>
    <!-- Header -->
    @include('partials/header')

    <!-- Main Content --> 
    <main>
        <div class="container py-4">
            <div class="row">
                <!-- Sidebar Navigation -->
                <div class="col-md-3 mb-3">
                    <div class="list-group sidebar">
                        <a href="{{ url('AdminProductManagement') }}" class="list-group-item list-group-item-action">Products</a>
                        <a href="{{ url('AdminOrderManagement') }}" class="list-group-item list-group-item-action active">Orders</a>
                    </div>
                </div>
        
                <div class="col-md-9">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h1 class="mb-0 h5">Order Management</h1>
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm" style="width: auto;">
                                    <option>All Status</option>
                                    <option>Pending</option>
                                    <option>Processing</option>
                                    <option>Shipped</option>
                                    <option>Delivered</option>
                                    <option>Cancelled</option>
                                </select>
                                <button class="btn btn-outline-primary btn-sm">Filter</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer ID</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>#12345</td>
                                            <td>#54321</td>
                                            <td>2024-03-15</td>
                                            <td>$256</td>
                                            <td>
                                                <span class="badge bg-warning text-dark">Processing</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>#12346</td>
                                            <td>#32154</td>
                                            <td>2024-03-14</td>
                                            <td>$128</td>
                                            <td>
                                                <span class="badge bg-success">Delivered</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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