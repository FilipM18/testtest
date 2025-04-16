<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In & Sign Up</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>

  <!-- Sign In Page -->
  <main>
    <div class="page-container" id="signin-page" style="{{ isset($signup) ? 'display:none;' : 'display:flex;' }}">
      <div class="auth-container">
        <div class="logo">
          <img src="{{ asset('images/Dressify.png') }}" alt="Dressify Logo" />
          <p>ONLINE CLOTHES SHOP</p>
        </div>
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
          @csrf
          <div class="mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email address" required value="{{ old('email') }}" />
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required />
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3 d-flex justify-content-between align-items-center">
            <div class="form-check">
              <input type="checkbox" name="remember_me" class="form-check-input" id="remember-me" />
              <label class="form-check-label" for="remember-me">Remember me</label>
            </div>
            <a href="{{ route('password.request') }}" class="forgot-password">Forgot password?</a>
          </div>
          <button type="submit" class="btn btn-primary w-100">Sign in</button>
        </form>
        <div class="auth-switch">
          <p>Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
        </div>
      </div>
    </div>
  
    <!-- Sign Up Page -->
    <div class="page-container" id="signup-page" style="{{ isset($signup) ? 'display:flex;' : 'display:none;' }}">
      <div class="auth-container">
        <div class="logo">
          <img src="{{ asset('images/Dressify.png') }}" alt="Dressify Logo" />
          <p>Create your account</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}">
          @csrf
          <div class="row mb-3">
            <div class="col">
              <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" placeholder="First name" required value="{{ old('first_name') }}" />
              @error('first_name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="col">
              <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" placeholder="Last name" required value="{{ old('last_name') }}" />
              @error('last_name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email address" required value="{{ old('email') }}" />
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required />
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required />
          </div>
          <button type="submit" class="btn btn-primary w-100">Sign up</button>
        </form>
        <div class="auth-switch">
          <p>Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
        </div>
      </div>
    </div>
  </main>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>