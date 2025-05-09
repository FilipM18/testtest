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

        <h1 class="mb-4 fw-bold pb-4">Shopping Cart</h1>

        <div class="row g-5">
            <!-- Cart Items -->  
            <div class="row">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    @if(count($cart->items) > 0)
                    <form id="cart-update-form" action="{{ route('cart.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Product</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->variant->product->image_url)
                                                        <img src="{{ asset($item->variant->product->first_image) }}" class="product-image me-3" alt="{{ $item->variant->product->name }}">
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
                                                <input 
                                                    type="number" 
                                                    name="items[{{ $item->cart_item_id }}][quantity]" 
                                                    value="{{ $item->quantity }}" 
                                                    min="0" 
                                                    class="form-control cart-qty" 
                                                    style="width: 80px;"
                                                    data-product="{{ $item->variant->product->name }}"
                                                >
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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                    @else
                        <div class="alert alert-info mt-5">
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
                            
                            <form action="{{ route('checkout') }}" method="GET">
                                <button type="submit" class="btn btn-success w-100 mb-3">Checkout</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('partials/footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let form = document.getElementById('cart-update-form');
            let qtyInputs = form.querySelectorAll('.cart-qty');
            let lastValues = {};
        
            qtyInputs.forEach(function(input) {
                lastValues[input.name] = input.value;
        
                input.addEventListener('focus', function() {
                    lastValues[input.name] = input.value;
                });
        
                input.addEventListener('change', function(e) {
                    if (parseInt(input.value, 10) === 0) {
                        let product = input.getAttribute('data-product') || 'this product';
                        if (!confirm('Set quantity to zero? This will remove ' + product + ' from your cart.')) {
                            input.value = lastValues[input.name];
                            return;
                        }
                    }
                    form.submit();
                });
            });
        });
        </script>
</body>

</html>
