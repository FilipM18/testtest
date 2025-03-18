<nav class="navbar navbar-expand-md navbar-light bg-light border-bottom">
    <div class="container-fluid d-flex m-0 me-2">
        <!-- Left: Hamburger menu for small screens + Logo -->
        <div class="d-flex align-items-center">
            <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Logo (left-aligned) -->
            <a class="navbar-brand" href="#">
                <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="" height="40">
            </a>
        </div>

        <!-- Center: Navbar links -->
        <ul class="navbar-nav d-none d-md-flex gap-3 ">
            <li class="nav-item"><a class="nav-link" href="#">Women</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Men</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Children</a></li>
        </ul>

        <!-- Right: Icons -->
        <div class="d-flex align-items-center ms-auto">
            <a href="#" class="text-muted me-3"><i class="bi bi-search fs-5"></i></a>
            <a href="#" class="text-muted me-3"><i class="bi bi-person fs-5"></i></a>

            <!-- Divider -->
            <span class="mx-2" style="height: 1.5rem; width: 1px; background-color: #e0e0e0;"></span>

            <a href="#" class="text-muted d-flex align-items-center">
                <i class="bi bi-cart3 fs-5"></i>
                <span class="ms-2 text-dark fw-medium">0</span>
            </a>
        </div>

        <!-- Dropdown menu -->
        <div class="d-md-none">
            <div class="collapse navbar-collapse position-absolute bg-light p-3 rounded shadow-sm"
                id="navbarNav" style="width: 250px; top: 100%; left: 0; z-index: 1000;">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#">Women</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Men</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Kids</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>