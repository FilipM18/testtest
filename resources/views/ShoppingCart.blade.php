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

    <main class="container py-5">
        <h1 class="mb-4 fw-bold">Shopping Cart</h1>
        <hr class="mb-4">

        <div class="row g-5">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <!-- Item 1 -->
                <div class="cart-item row align-items-center">
                    <div class="col-md-2 col-4 mb-3 mb-md-0 p-0">
                        <img src="images/blueGant.png" class="product-image img-fluid" alt="Basic Tee Blue">
                    </div>
                    <div class="col-md-6 col-8 mb-3 mb-md-0">
                        <h5 class="mb-1">Basic Tee</h5>
                        <div class="text-muted mb-1">Blue • Large</div>
                        <div class="fw-bold">$32.00</div>
                        <div class="mt-2 d-flex align-items-center">
                            <span class="status-icon in-stock me-2"><i class="bi bi-check-circle-fill"></i></span>
                            <span class="small">In stock</span>
                        </div>
                    </div>
                    <div class="col-md-2 col-6 mb-3 mb-md-0">
                        <select class="form-select form-select-sm quantity-select">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-6 text-end mb-3 mb-md-0">
                        <button class="remove-btn"><i class="bi bi-x"></i></button>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="cart-item row align-items-center">
                    <div class="col-md-2 col-4 mb-3 mb-md-0 p-0">
                        <img src="images/whiteGant.avif" class="product-image img-fluid"
                            alt="Basic Tee White">
                    </div>
                    <div class="col-md-6 col-8 mb-3 mb-md-0">
                        <h5 class="mb-1">Basic Tee</h5>
                        <div class="text-muted mb-1">White • Large</div>
                        <div class="fw-bold">$32.00</div>
                        <div class="mt-2 d-flex align-items-center">
                            <span class="status-icon shipping me-2"><i class="bi bi-clock-fill"></i></span>
                            <span class="small">Ships in 3–4 weeks</span>
                        </div>
                    </div>
                    <div class="col-md-2 col-6 mb-3 mb-md-0">
                        <select class="form-select form-select-sm quantity-select">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-6 text-end mb-3 mb-md-0">
                        <button class="remove-btn"><i class="bi bi-x"></i></button>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="cart-item row align-items-center">
                    <div class="col-md-2 col-4 mb-3 mb-md-0 p-0">
                        <img src="images/greenGant.avif" class="product-image img-fluid" alt="Basic Tee Green">
                    </div>
                    <div class="col-md-6 col-8 mb-3 mb-md-0">
                        <h5 class="mb-1">Basic Tee</h5>
                        <div class="text-muted mb-1">Green • Large</div>
                        <div class="fw-bold">$35.00</div>
                        <div class="mt-2 d-flex align-items-center">
                            <span class="status-icon in-stock me-2"><i class="bi bi-check-circle-fill"></i></span>
                            <span class="small">In stock</span>
                        </div>
                    </div>
                    <div class="col-md-2 col-6 mb-3 mb-md-0">
                        <select class="form-select form-select-sm quantity-select">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-6 text-end mb-3 mb-md-0">
                        <button class="remove-btn"><i class="bi bi-x"></i></button>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="order-summary">
                    <h4 class="mb-4">Order summary</h4>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal</span>
                        <span class="fw-bold">$99.00</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <span>Shipping estimate</span>
                            <i class="bi bi-info-circle info-icon ms-1"></i>
                        </div>
                        <span class="fw-bold">$5.00</span>
                    </div>

                    <div class="d-flex justify-content-between mb-4">
                        <div>
                            <span>Tax estimate</span>
                            <i class="bi bi-info-circle info-icon ms-1"></i>
                        </div>
                        <span class="fw-bold">$8.32</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-4">
                        <span>Order total</span>
                        <span class="fw-bold">$112.32</span>
                    </div>
                    <a href="{{url('CheckoutPage')}}" class="btn btn-primary checkout-btn text-white w-100 py-2">Checkout</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('partials/footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>