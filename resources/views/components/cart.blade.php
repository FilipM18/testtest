<div class="col-lg-8 mb-4 mb-lg-0">
    @if(count($items) > 0)
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
                    @foreach($items as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item->image)
                                        <img src="{{ asset($item->image) }}" class="product-image me-3" alt="{{ $item->name }}">
                                    @else
                                        <div class="bg-light me-3" style="width: 80px; height: 80px;"></div>
                                    @endif
                                    <span>{{ $item->name }}</span>
                                </div>
                            </td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td>
                                <input type="number" name="items[{{ $item->id }}][quantity]" value="{{ $item->quantity }}" min="0" class="form-control" style="width: 80px;">
                            </td>
                            <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                            <td>
                                <span class="status-icon {{ $item->status === 'in-stock' ? 'in-stock' : 'shipping' }}">
                                    <i class="bi {{ $item->status === 'in-stock' ? 'bi-check-circle-fill' : 'bi-truck' }}"></i>
                                    {{ $item->status === 'in-stock' ? 'In Stock' : 'Shipping' }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
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
            Your cart is empty. <a href="{{ url('/products') }}">Continue shopping</a>
        </div>
    @endif
</div>
