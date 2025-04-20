@include('partials/header')

<div class="container py-5">
  <div class="text-center mb-5">
    <div class="mb-4">
      <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
    </div>
    <h1>Thank you for your order!</h1>
    <p class="lead">Your order #{{ $order->order_id }} has been placed successfully.</p>
  </div>
  
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card mb-4">
        <div class="card-header">
          <h3 class="mb-0">Order Summary</h3>
        </div>
        <div class="card-body">
          <div class="mb-4">
            <h4>Order Details</h4>
            <p><strong>Order Number:</strong> #{{ $order->order_id }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('F j, Y, g:i a') }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
            <p><strong>Order Status:</strong> {{ ucfirst($order->status) }}</p>
          </div>
          
          <div class="mb-4">
            <h4>Order Items</h4>
            @foreach($order->orderItems as $item)
            <div class="d-flex mb-3 pb-3 border-bottom">
              <div class="me-3">
                @if($item->variant->product->image_url)
                <img src="{{ asset($item->variant->product->image_url) }}" alt="{{ $item->variant->product->name }}" style="width: 60px; height: 60px; object-fit: cover;">
                @else
                <div class="bg-light" style="width: 60px; height: 60px;"></div>
                @endif
              </div>
              <div class="flex-grow-1">
                <div class="d-flex justify-content-between">
                  <div>
                    <h5 class="mb-0">{{ $item->variant->product->name }}</h5>
                    <div class="text-muted">{{ $item->variant->color }} - {{ $item->variant->size }}</div>
                    <div>Quantity: {{ $item->quantity }}</div>
                  </div>
                  <div class="text-end">
                    <div>${{ number_format($item->price, 2) }}</div>
                    <div class="fw-bold">${{ number_format($item->price * $item->quantity, 2) }}</div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          
          <div class="mb-0">
            <div class="d-flex justify-content-between mb-2">
              <span>Subtotal</span>
              <span>${{ number_format($order->orderItems->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span>Shipping</span>
              <span>${{ number_format($order->total_amount - ($order->orderItems->sum(function($item) { return $item->price * $item->quantity; }) * 1.1), 2) }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span>Taxes</span>
              <span>${{ number_format($order->orderItems->sum(function($item) { return $item->price * $item->quantity * 0.1; }), 2) }}</span>
            </div>
            <div class="d-flex justify-content-between fw-bold">
              <span>Total</span>
              <span>${{ number_format($order->total_amount, 2) }}</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="text-center">
        <a href="{{ url('/') }}" class="btn btn-primary">Continue Shopping</a>
      </div>
    </div>
  </div>
</div>

@include('partials/footer')
