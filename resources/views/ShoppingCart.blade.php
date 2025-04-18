<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dressify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/app.css">
    <style>
        .status-icon.in-stock { color: #10b981; }
        .status-icon.out-of-stock { color: #ef4444; }
        .remove-btn {
            background: transparent;
            border: none;
            color: #ef4444;
            cursor: pointer;
        }
        .product-image {
            max-height: 80px;
            object-fit: contain;
        }
    </style>
</head>

<body>
    <!-- Header -->
    @include('partials/header')

    <main class="container py-5">
        <h1 class="mb-4 fw-bold">Shopping Cart</h1>
        <hr class="mb-4">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="row g-5">
            <!-- Cart Items -->
            <form action="{{ route('cart.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-lg-8 mb-4 mb-lg-0">
                        @if(count($cart->items) > 0)
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Product</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Total</th>
                                            <th scope="col">Status</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cart->items as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($item->variant->product->image_url)
                                                            <img src="{{ asset($item->variant->product->image_url) }}" class="product-image me-3" alt="{{ $item->variant->product->name }}">
                                                        @else
                                                            <div class="bg-light me-3" style="width: 80px; height: 80px;"></div>
                                                        @endif
                                                        <div>
                                                            <span>{{ $item->variant->product->name }}</span>
                                                            <small class="d-block text-muted">
                                                                {{ $item->variant->size }} / {{ $item->variant->color }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>${{ number_format($item->variant->product->price, 2) }}</td>
                                                <td>
                                                    <input type="number" name="items[{{ $item->cart_item_id }}][quantity]" value="{{ $item->quantity }}" min="0" class="form-control" style="width: 80px;">
                                                </td>
                                                <td>${{ number_format($item->variant->product->price * $item->quantity, 2) }}</td>
                                                <td>
                                                    @if($item->variant->stock_quantity > 0)
                                                        <span class="status-icon in-stock">
                                                            <i class="bi bi-check-circle-fill"></i>
                                                            In Stock
                                                        </span>
                                                    @else
                                                        <span class="status-icon out-of-stock">
                                                            <i class="bi bi-x-circle-fill"></i>
                                                            Out of Stock
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <form action="{{ route('cart.remove', $item->cart_item_id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="remove-btn">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Your cart is empty. <a href="{{ url('/CategoryPage') }}">Continue shopping</a>
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Order Summary</h5>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal</span>
                                    <span>${{ number_format($cart->items->sum(function($item) { 
                                        return $item->variant->product->price * $item->quantity; 
                                    }), 2) }}</span>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping</span>
                                    <span>Free</span>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-4">
                                    <span>Tax</span>
                                    <span>${{ number_format($cart->items->sum(function($item) { 
                                        return $item->variant->product->price * $item->quantity * 0.1; 
                                    }), 2) }}</span>
                                </div>
                                
                                <div class="d-flex justify-content-between fw-bold mb-4">
                                    <span>Total</span>
                                    <span>${{ number_format($cart->items->sum(function($item) { 
                                        return $item->variant->product->price * $item->quantity * 1.1; 
                                    }), 2) }}</span>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100 mb-3">Update Cart</button>
                                <form action="{{ route('checkout') }}" method="GET">
                                    <button type="submit" class="btn btn-success">Checkout</button>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
            <div class="mt-4">
                <a href="{{ route('cart.populate') }}" class="btn btn-outline-secondary">Add Sample Items</a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('partials/footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
