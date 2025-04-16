<header>
    <nav class="navbar navbar-expand-md navbar-light bg-light border-bottom">
        <div class="container-fluid d-flex m-0 me-2">
            <!-- Left: Hamburger on md- -->
            <button class="navbar-toggler d-md-none me-2" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#navbarOffcanvas" aria-controls="navbarOffcanvas">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Logo centered on md-, left on md+) -->
            <a class="navbar-brand mx-auto mx-md-0" href=" {{ url('../Home') }}">
                <img src="images/Dressify.png" alt="Dressify Logo" height="40">
            </a>

            <!-- Center: Navbar links, only on md+ -->
            <div class="collapse navbar-collapse d-none d-md-flex justify-content-center">
                <ul class="navbar-nav gap-3">
                    <li class="nav-item"><a class="nav-link" href="{{ url('../CategoryPage') }}">Women</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('../CategoryPage') }}">Men</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('../CategoryPage') }}">Kids</a></li>
                </ul>
            </div>

            <!-- Right: Icons -->
            <div class="d-flex align-items-center">
                <a href="#" class="text-muted me-3"><i class="bi bi-search fs-5"></i></a>
                
                <!-- User dropdown menu -->
                <div class="dropdown">
                    <a href="#" class="text-muted me-3 dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person fs-5"></i>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        @guest
                            <!-- Not logged in - show login/register options -->
                            <li><a class="dropdown-item" href="{{ route('login') }}">Sign In</a></li>
                            <li><a class="dropdown-item" href="{{ route('register') }}">Sign Up</a></li>
                        @else
                            <!-- Logged in - show user info and logout -->
                            <li>
                                <span class="dropdown-item-text">Hello, {{ Auth::user()->name }}</span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            
                            @if(Auth::user()->role === 'admin')
                                <!-- Admin-specific options -->
                                <li><a class="dropdown-item" href="/AdminOrderManagment">Order Management</a></li>
                                <li><a class="dropdown-item" href="/AdminProductManagment">Product Management</a></li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            
                            <!-- Logout option -->
                            <li>
                                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                    <a class="dropdown-item" href="#" 
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>

                <!-- Divider -->
                <span class="mx-2" style="height: 1.5rem; width: 1px; background-color: #e0e0e0;"></span>

                <a href="{{ url('../ShoppingCart') }}" class="text-muted d-flex align-items-center">
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
                <li class="nav-item"><a class="nav-link" href="{{ url('../CategoryPage') }}">Women</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('../CategoryPage') }}">Men</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('../CategoryPage') }}">Kids</a></li>
            </ul>
            <hr>
            <div class="d-flex flex-column gap-2">
                @guest
                    <a href="{{ route('login') }}" class="text-decoration-none text-dark">Sign in</a>
                    <a href="{{ route('register') }}" class="text-decoration-none text-dark">Create account</a>
                @else
                    <span class="text-dark">Hello, {{ Auth::user()->name }}</span>
                    
                    @if(Auth::user()->role === 'admin')
                        <a href="/AdminOrderManagment" class="text-decoration-none text-dark">Order Management</a>
                        <a href="/AdminProductManagment" class="text-decoration-none text-dark">Product Management</a>
                    @endif
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="#" class="text-decoration-none text-dark" 
                           onclick="event.preventDefault(); this.closest('form').submit();">
                            Logout
                        </a>
                    </form>
                @endguest
            </div>
        </div>
    </div>
</header>
