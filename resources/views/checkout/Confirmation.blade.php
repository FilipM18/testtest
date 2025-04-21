<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    @include('partials/header')

    <div class="container py-5 confirmation-container">
        <div class="text-center mb-5">
            <div class="mb-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
            </div>
            <h1>Thank you for your order!</h1>
            <p class="lead">Your order #{{ $order->order_id }} has been placed successfully.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4 order-confirmation-card">
                    <div class="card-header ">
                        <h3 class="mb-0">Order Summary</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h4>Order Details</h4>
                            <p><strong>Order Number:</strong> #{{ $order->order_id }}</p>
                            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('F j, Y, g:i a') }}</p>
                            <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                            <p><strong>Order Status:</strong> {{ ucfirst($order->status) }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <h4>Order Items</h4>
                            @foreach($order->orderItems as $item)
<div class="order-item">
    <div class="order-item-image">
        @if($item->variant->product->image_url)
        <img src="{{ asset($item->variant->product->image_url) }}" alt="{{ $item->variant->product->name }}">
        @else
        <div class="bg-light" style="width: 100%; height: 100%;"></div>
        @endif
    </div>
    <div class="order-item-details">
        <h5 class="order-item-name">{{ $item->variant->product->name }}</h5>
        <div class="order-item-variant">{{ $item->variant->color }} - {{ $item->variant->size }}</div>
        <div>Quantity: {{ $item->quantity }}</div>
    </div>
    <div class="order-item-price">
        <div>Price: ${{ number_format($item->price, 2) }}</div>
        <div class="fw-bold">Total: ${{ number_format($item->price * $item->quantity, 2) }}</div>
    </div>
</div>
@endforeach

                        </div>
                        
                        <div class="order-summary-section">
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span>${{ number_format($order->orderItems->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping</span>
                                <span>${{ number_format($order->total_amount - ($order->orderItems->sum(function($item) { return $item->price * $item->quantity; }) * 1.1), 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <span>Taxes</span>
                                <span>${{ number_format($order->orderItems->sum(function($item) { return $item->price * $item->quantity * 0.1; }), 2) }}</span>
                            </div>
                            <div class="summary-row total">
                                <span>Total</span>
                                <span>${{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    <a href="{{ url('/') }}" class="btn continue-shopping-btn">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>

    @include('partials/footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>