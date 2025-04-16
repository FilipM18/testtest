<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset Password - Dressify</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
</head>

<body>
  <main>
    <div class="page-container" style="display:flex;">
      <div class="auth-container">
        <div class="logo">
          <img src="{{ asset('images/Dressify.png') }}" alt="Dressify Logo" />
          <p>Reset your password</p>
        </div>

        @if (session('status'))
          <div class="alert alert-success mb-3">
            {{ session('status') }}
          </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
          @csrf
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus />
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <button type="submit" class="btn btn-primary w-100">Send Password Reset Link</button>
          </div>
        </form>
        <div class="auth-switch">
          <p>Remember your password? <a href="{{ route('login') }}">Back to login</a></p>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>