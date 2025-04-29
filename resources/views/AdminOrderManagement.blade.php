<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dressify - Admin Orders Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <!-- Header -->
    @include('partials.header')

    <!-- Main Content --> 
    <main>
        <div class="container py-4">
            <div class="row">
                <!-- Sidebar Navigation -->
                <div class="col-md-3 mb-3">
                    <div class="list-group sidebar">
                        <a href="{{ route('admin.products') }}" class="list-group-item list-group-item-action">Products</a>
                        <a href="{{ route('admin.orders') }}" class="list-group-item list-group-item-action active">Orders</a>
                    </div>
                </div>
        
                <!-- Orders Table -->
                <div class="col-md-9">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h1 class="mb-0 h5">Order Management</h1>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td>#{{ $order->order_id }}</td>
                                        <td>
                                            @if($order->user)
                                                {{ $order->user->name }}<br>
                                                <small>{{ $order->user->email }}</small>
                                            @else
                                                Guest
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                        <td>${{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($order->status == 'Pending') bg-warning text-dark
                                                @elseif($order->status == 'Processing') bg-primary
                                                @elseif($order->status == 'Shipped') bg-info
                                                @elseif($order->status == 'Delivered') bg-success
                                                @elseif($order->status == 'Cancelled') bg-danger
                                                @else bg-secondary
                                                @endif
                                            ">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @if($orders->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No orders found.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
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