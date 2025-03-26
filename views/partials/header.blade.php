<!--pozn. blade ešte nie je implmentovaný, partials sú zatiaľ iba pripravené na 2. fázu: backend-laravel-->
<header>
    <nav class="navbar navbar-expand-md navbar-light bg-light border-bottom">
        <div class="container-fluid d-flex m-0 me-2">
            <!-- Left: Hamburger on md- -->
            <button class="navbar-toggler d-md-none me-2" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#navbarOffcanvas" aria-controls="navbarOffcanvas">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Logo centered on md-, left on md+) -->
            <a class="navbar-brand mx-auto mx-md-0" href="">
                <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="" height="40">
            </a>

            <!-- Center: Navbar links, only on md+ -->
            <div class="collapse navbar-collapse d-none d-md-flex justify-content-center">
                <ul class="navbar-nav gap-3">
                    <li class="nav-item"><a class="nav-link" href="#">Women</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Men</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Kids</a></li>
                </ul>
            </div>

            <!-- Right: Icons -->
            <div class="d-flex align-items-center">
                <a href="#" class="text-muted me-3"><i class="bi bi-search fs-5"></i></a>
                <a href="#" class="text-muted me-3"><i class="bi bi-person fs-5"></i></a>

                <!-- Divider -->
                <span class="mx-2" style="height: 1.5rem; width: 1px; background-color: #e0e0e0;"></span>

                <a href="#" class="text-muted d-flex align-items-center">
                    <i class="bi bi-bag"></i>
                    <span class="top-50 start-100 translate-middle badge rounded-pill bg-light text-dark">
                        3
                    </span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Offcanvas Menu only on md- -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="navbarOffcanvas" aria-labelledby="navbarOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="navbarOffcanvasLabel">Menu</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="#">Women</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Men</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Kids</a></li>
            </ul>
            <hr>
            <div class="d-flex flex-column gap-2">
                <a href="#" class="text-decoration-none text-dark">Sign in</a>
                <a href="#" class="text-decoration-none text-dark">Create account</a>
            </div>
        </div>
    </div>
</header>