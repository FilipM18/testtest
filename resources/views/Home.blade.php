<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/app.css">
</head>

<body>
    <!-- Header -->
    @include('partials/header')

    <main>
        <!-- Inspired by: https://getbootstrap.com/docs/5.3/examples/carousel/ -->
        <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class=""
                    aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2" class="active"
                    aria-current="true"></button>
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"
                    class=""></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item">
                    <img src="images/wide.avif" class="d-block w-100" alt="Black hoodie with logo">
                    <div class="container">
                        <div class="carousel-caption text-start">
                            <h1>Elevate Your Style This Season</h1>
                            <p class="opacity-75">Discover our curated collection of premium essentials that blend comfort with contemporary design.</p>
                            <p><a class="btn btn-lg btn-primary" href="SignIn.html">Sign up today</a></p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item active">
                    <img src="images/wide2.jpg" class="d-block w-100" alt="3-pack hoodies white, brown and green">
                    <div class="container">
                        <div class="carousel-caption">
                            <h1>Sustainable Fashion, Exceptional Quality</h1>
                            <p>Our eco-conscious pieces are crafted to last, combining ethical production with timeless aesthetics.</p>
                            <p><a class="btn btn-lg btn-primary" href="ProductInfo.html">Explore New Arrival</a></p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="images/wide3.jpg" class="d-block w-100" alt="3-pack hoodies black, gray and green">
                    <div class="container">
                        <div class="carousel-caption text-end">
                            <h1>Express Yourself Through Fashion</h1>
                            <p>Find your unique voice with our diverse range of styles that celebrate individuality and confidence.</p>
                            <p><a class="btn btn-lg btn-primary" href="CategoryPage.html">Shop Collections</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="container py-5">
            <div class="row featurette align-items-center">
                <div class="col-md-7">
                    <h2 class="featurette-heading fw-normal lh-1">Effortless Elegance <span
                            class="text-body-secondary">It'll blow your mind.</span></h2>
                    <p class="lead">Discover clothing that combines sophistication with comfort. Our designs are tailored to impress and inspire confidence.</p>
                    <a href="ProductInfo.html" class="btn btn-outline-primary my-2">Learn more</a>
                </div>
                <div class="col-md-5">
                    <img src="images/landingPagePhoto.jpg" class="img-fluid rounded shadow" alt="Clothes on racks">
                </div>
            </div>

            <hr class="featurette-divider">

            <div class="row featurette align-items-center">
                <div class="col-md-5 order-md-2">
                    <h2 class="featurette-heading fw-normal lh-1">Quality You Can Trust<span
                            class="text-body-secondary">See for yourself.</span></h2>
                    <p class="lead">Every piece is crafted with care to ensure durability and timeless appeal. See what makes us stand out in the world of fashion.</p>
                    <a href="CategoryPage.html" class="btn btn-outline-primary my-2">Explore collection</a>
                </div>
                <div class="col-md-7 order-md-1">
                    <img src="images/landingPagePhoto.jpg" class="img-fluid rounded shadow" alt="Clothes on racks">
                </div>
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    @include('partials/footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>