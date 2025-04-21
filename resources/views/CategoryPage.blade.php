<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dressify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/app.css">
</head>

<body>
    <!-- Header -->
    @include('partials/header')

    <!-- Main Content -->
    <main>
        <div class="container-fluid px-0">
            <!-- Mobile Navigation -->
            <div class="d-md-none ms-2">
                <nav class="navbar navbar-expand-md navbar-light bg-white border-bottom">
                    <div class="container-fluid">
                        <button class="btn btn-outline-dark ms-auto me-2" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#filterOffcanvas" aria-controls="filterOffcanvas">
                            <span class="filter-btn">
                                <span>Filters</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-plus" viewBox="0 0 16 16">
                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </nav>
            </div>

            <div class="row mb-3 align-items-center">
                <div class="col-12 col-md-3">
                    <h4 class="mb-0 ms-2">Products <small class="text-muted">({{ $products->total() }} items)</small></h4>
                </div>
                <div class="col-12 col-md-6 d-flex justify-content-md-start mt-2 mt-md-0 py-4">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Sort by: {{ request('sort', 'Default') }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                            <li><a class="dropdown-item {{ request('sort') == null ? 'active' : '' }}" href="{{ route('category.index', request()->except('sort', 'page')) }}">Default</a></li>
                            <li><a class="dropdown-item {{ request('sort') == 'price_low' ? 'active' : '' }}" href="{{ route('category.index', array_merge(request()->except('sort', 'page'), ['sort' => 'price_low'])) }}">Price: Low to High</a></li>
                            <li><a class="dropdown-item {{ request('sort') == 'price_high' ? 'active' : '' }}" href="{{ route('category.index', array_merge(request()->except('sort', 'page'), ['sort' => 'price_high'])) }}">Price: High to Low</a></li>
                            <li><a class="dropdown-item {{ request('sort') == 'newest' ? 'active' : '' }}" href="{{ route('category.index', array_merge(request()->except('sort', 'page'), ['sort' => 'newest'])) }}">Newest</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Mobile Filters Offcanvas -->
            <x-mobile-filters 
                :color-options="$colorOptions" 
                :category-options="$categoryOptions" 
                :size-options="$sizeOptions" 
            />

            <div class="container-fluid py-4">
                <div class="row g-4">
                    <div class="col-md-3 d-none d-md-block">
                        <x-sidebar 
                            :color-options="$colorOptions" 
                            :category-options="$categoryOptions" 
                            :size-options="$sizeOptions" 
                        />
                    </div>
                    <div class="col-md-9">
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                            @foreach($products as $product)
                                <x-product-card 
                                    :image="$product->image_url"
                                    :title="$product->name"
                                    :description="Str::limit($product->description, 100)"
                                    :colors="$product->variants->unique('color')->count()"
                                    :price="$product->price"
                                    :id="$product->product_id"
                                />
                            @endforeach
                        </div>
                        
                        @if($products->count() == 0)
                        <div class="text-center py-5">
                            <i class="bi bi-exclamation-circle fs-1 text-muted"></i>
                            <h4 class="mt-3">No products found</h4>
                            <p class="text-muted">Try adjusting your filters to find what you're looking for.</p>
                            <a href="{{ route('category.index') }}" class="btn btn-outline-primary mt-2">Clear All Filters</a>
                        </div>
                        @endif
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $products->appends(request()->query())->links("components.pagination") }}
                        </div>
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