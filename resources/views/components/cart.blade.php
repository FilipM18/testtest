<div class="col-lg-8">
    @forelse($items as $item)
        <x-cart-item :item="$item" />
    @empty
        <div class="alert alert-info">Your cart is empty.</div>
    @endforelse
</div>
