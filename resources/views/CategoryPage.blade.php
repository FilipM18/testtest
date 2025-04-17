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
        <div class="d-md-none">
            <nav class="navbar navbar-expand-md navbar-light bg-white border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-outline-dark ms-auto me-2" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#filterOffcanvas" aria-controls="filterOffcanvas">
                        <span class="filter-btn">
                            <span>Filters</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-plus" viewBox="0 0 16 16">
                                <path
                                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                            </svg>
                        </span>
                    </button>
                </div>
            </nav>
        </div>

        <!-- Mobile Filters Offcanvas -->
        <x-mobile-filters 
            :color-options="$colorOptions" 
            :category-options="$categoryOptions" 
            :size-options="$sizeOptions" 
        />

        <div class="container py-4">
            <div class="row g-4">
                <!-- Desktop Sidebar -->
                <div class="col-md-3 d-none d-md-block">
                    <x-sidebar 
                        :color-options="$colorOptions" 
                        :category-options="$categoryOptions" 
                        :size-options="$sizeOptions" 
                    />
                </div>
                <!-- Product Grid -->
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
                    
                    <!-- Use Laravel's built-in pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $products->appends(request()->query())->links() }}
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