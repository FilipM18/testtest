<a href="{{ $url }}" class="text-decoration-none text-dark">
    <div class="col">
        <div class="card h-100 product-card border-0">
            <img src="{{ $image }}" class="card-img-top product-img" alt="{{ $title }}">
            <div class="card-body p-3">
                <h5 class="product-title">{{ $title }}</h5>
                <p class="card-text text-muted">{{ $description }}</p>
                <p class="product-color mb-1">{{ $colors }} colors</p>
                <p class="price">${{ $price }}</p>
            </div>
        </div>
    </div>
</a>
