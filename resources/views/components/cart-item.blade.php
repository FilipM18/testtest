<div class="cart-item row align-items-center mb-4 pb-3 border-bottom">
    <div class="col-md-2 col-4 mb-3 mb-md-0 p-0">
        <img src="{{ asset($item['image']) }}" class="product-image img-fluid" alt="{{ $item['name'] }} {{ $item['color'] }}">
    </div>
    <div class="col-md-6 col-8 mb-3 mb-md-0">
        <h5 class="mb-1">{{ $item['name'] }}</h5>
        <div class="text-muted mb-1">{{ $item['color'] }} • {{ $item['size'] }}</div>
        <div class="fw-bold">${{ number_format($item['price'], 2) }}</div>
        <div class="mt-2 d-flex align-items-center">
            @if($item['status'] == 'in-stock')
                <span class="status-icon in-stock me-2"><i class="bi bi-check-circle-fill"></i></span>
                <span class="small">{{ $item['status_message'] ?? 'In stock' }}</span>
            @else
                <span class="status-icon shipping me-2"><i class="bi bi-clock-fill"></i></span>
                <span class="small">{{ $item['status_message'] ?? 'Ships in 3–4 weeks' }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-2 col-6 mb-3 mb-md-0">
        <select class="form-select form-select-sm quantity-select" 
                name="items[{{ $item['id'] }}][quantity]" 
                data-item-id="{{ $item['id'] }}">
            @for($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ $item['quantity'] == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>
    </div>
    <div class="col-md-2 col-6 text-end mb-3 mb-md-0">
        <form action="{{ route('cart.remove', $item['id']) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="remove-btn btn btn-sm btn-outline-danger"><i class="bi bi-x"></i></button>
        </form>
    </div>
</div>
