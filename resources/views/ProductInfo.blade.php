<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Dressify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <!-- Header -->
    @include('partials/header')

    <!-- Main Body -->
    <main class="container py-5">
        <div class="row">
            <!-- Product Image -->
            <div class="col-lg-7">
                <h2 class="visually-hidden">Images</h2>
                <div class="row g-2">

                    <div class="col-12">
                        <img src="{{ asset($product->image_url) }}" class="img-fluid rounded" alt="{{ $product->name }}">
                    </div>
                    @if(isset($product->additional_images) && count($product->additional_images) > 0)
                        @foreach($product->additional_images as $index => $image)
                            @if($index < 2)
                                <div class="col-6">
                                    <img src="{{ asset($image) }}" class="img-fluid rounded" alt="{{ $product->name }} view {{ $index + 1 }}">
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="col-lg-5">
                <div class="d-flex justify-content-between mt-3">
                    <h1 class="h3">{{ $product->name }}</h1>
                </div>
                <p class="fs-4 mb-4">${{ number_format($product->price, 2) }}</p>
                
                @if($product->brand)
                    <p class="text-muted mb-3">{{ $product->brand->name }}</p>
                @endif

                <div class="mt-3">
                    <h2 class="visually-hidden">Reviews</h2>
                    <div class="d-flex align-items-center">
                        @php
                            $avgRating = $product->average_rating;
                            $reviewCount = $product->reviews->count();
                            $fullStars = floor($avgRating);
                            $hasHalfStar = $avgRating - $fullStars >= 0.5;
                        @endphp
                        <p class="text-muted mb-0">{{ number_format($avgRating, 1) }}</p>
                        <div class="ms-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $fullStars)
                                    <span class="text-warning">&#9733;</span>
                                @elseif($i == $fullStars + 1 && $hasHalfStar)
                                    <span class="text-warning">&#9733;&#9734;</span>
                                @else
                                    <span class="text-secondary">&#9733;</span>
                                @endif
                            @endfor
                        </div>
                        
                        <div class="ms-3">·</div>
                        <div class="ms-3">
                            <a href="#reviews" class="text-decoration-none text-primary">See all {{ $reviewCount }} reviews</a>
                        </div>
                    </div>
                </div>
               
                <p class="mt-3">{{ $product->description }}</p>
                
                <div class="mt-3">
                    <h6>Product Details</h6>
                    <ul>
                        <li>Material: 100% Cotton</li>
                        <li>Machine Washable</li>
                        <li>Unisex Fit</li>
                    </ul>
                </div>
                
                <div class="mt-3">
                    <h6>Policies</h6>
                    <ul>
                        <li>Free Shipping on orders over $50</li>
                        <li>30-day return policy</li>
                        <li>Secure payment options</li>
                    </ul>
                </div>

                <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
                    @csrf
                    @if(isset($variants) && count($variants) > 0)
                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                        <div class="mb-3">
                            <h6>Size/Color</h6>
                            <select name="variant_id" id="variant_id" class="form-select" required>
                                <option value="">Select an option</option>
                                @foreach($variants as $variant)
                                    <option value="{{ $variant->variant_id }}" data-stock="{{ $variant->stock_quantity }}">
                                        {{ $variant->size }} / {{ $variant->color }} 
                                        ({{ $variant->stock_quantity > 0 ? 'In Stock' : 'Out of Stock' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <!-- No variants available message -->
                        <div class="alert alert-warning mb-3">
                            <h6 class="alert-heading">No Variants Available</h6>
                            <p class="mb-0">This product is currently out of stock or has no available variants.</p>
                        </div>
                        <button type="button" class="btn btn-secondary w-100" disabled>Out of Stock</button>
                    @endif
                
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1">
                    </div>
                
                    @if(isset($variants) && count($variants) > 0)
                        <button type="submit" class="btn btn-primary w-100">Add to cart</button>
                    @endif
                </form>               

                <div class="mt-3" id="reviews">
                    <h6>Customer Reviews</h6>
                    @if($product->reviews->count() > 0)
                        @foreach($product->reviews as $review)
                            <div class="border p-3 rounded {{ !$loop->first ? 'mt-2' : '' }}">
                                <p><strong>{{ $review->user->name }}</strong> - 
                                    <span class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                &#9733;
                                            @else
                                                &#9734;
                                            @endif
                                        @endfor
                                    </span>
                                    <small class="text-muted ms-2">{{ $review->created_at->format('M d, Y') }}</small>
                                </p>
                                <p>"{{ $review->comment }}"</p>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            <p class="mb-0">This product doesn't have any reviews yet. Be the first to share your experience!</p>
                        </div>
                    @endif
                    <div class="text-center mt-3">
                        <a href="{{ route('reviews.create', ['product_id' => $product->product_id]) }}" class="btn btn-outline-primary">Write a Review</a>
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
