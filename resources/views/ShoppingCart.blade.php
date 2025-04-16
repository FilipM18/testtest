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
        .status-icon.shipping { color: #f59e0b; }
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

        <div class="row g-5">
            <!-- Cart Items -->
            <form action="{{ route('cart.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <x-cart :items="$cartItems" />
                    
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Order Summary</h5>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal</span>
                                    <span>${{ number_format($cartItems->sum(function($item) { 
                                        return $item['price'] * $item['quantity']; 
                                    }), 2) }}</span>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping</span>
                                    <span>Free</span>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-4">
                                    <span>Tax</span>
                                    <span>${{ number_format($cartItems->sum(function($item) { 
                                        return $item['price'] * $item['quantity'] * 0.1; 
                                    }), 2) }}</span>
                                </div>
                                
                                <div class="d-flex justify-content-between fw-bold mb-4">
                                    <span>Total</span>
                                    <span>${{ number_format($cartItems->sum(function($item) { 
                                        return $item['price'] * $item['quantity'] * 1.1; 
                                    }), 2) }}</span>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100 mb-3">Update Cart</button>
                                <a href="{{url('CheckoutPage')}}" class="btn btn-success w-100">Checkout</a>
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
