<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout Page</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="css/app.css">
</head>

<body>
  <!-- Header -->
  @include('partials/header')
  @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
  <div class="container py-5">
    <form method="POST" action="{{ route('checkout.complete') }}">
      @csrf
      <div class="row g-5">
        <div class="col-lg-7">
          <!-- Contact information -->
          <div class="mb-4">
            <h3 class="mb-3">Contact information</h3>
            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}">
            </div>
            <div class="mb-3">
              <label for="phone" class="form-label">Phone</label>
              <input type="text" class="form-control" id="phone" name="phone" required value="{{ old('email') }}">
            </div>
          </div>
          
          <!-- Shipping information -->
          <div class="mb-4">
            <h3 class="mb-3">Shipping information</h3>
            <div class="row mb-3">
              <div class="col-md-6 mb-3 mb-md-0">
                <label for="firstName" class="form-label">First name</label>
                <input type="text" class="form-control" id="firstName" name="first_name" required value="{{ old('first_name') }}">
              </div>
              <div class="col-md-6">
                <label for="lastName" class="form-label">Last name</label>
                <input type="text" class="form-control" id="lastName" name="last_name" required value="{{ old('last_name') }}">
              </div>
            </div>
            
            <div class="mb-3">
              <label for="address" class="form-label">Address</label>
              <input type="text" class="form-control" id="address" name="address" required value="{{ old('address') }}">
            </div>
            
            <div class="mb-3">
              <label for="apartment" class="form-label">Apartment, suite, etc.</label>
              <input type="text" class="form-control" id="apartment" name="apartment" value="{{ old('apartment') }}">
            </div>
            
            <div class="row mb-3">
              <div class="col-md-6 mb-3 mb-md-0">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" required value="{{ old('city') }}">
              </div>
              <div class="col-md-6">
                <label for="country" class="form-label">Country</label>
                <select class="form-select" id="country" name="country" required>
                  <option value="United States" {{ old('country') == 'United States' ? 'selected' : '' }}>United States</option>
                  <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                  <option value="Slovakia" {{ old('country') == 'Slovakia' ? 'selected' : '' }}>Slovakia</option>
                  <option value="Other" {{ old('country') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
              </div>
            </div>
            
            <div class="row mb-3">
              <div class="col-md-6 mb-3 mb-md-0">
                <label for="state" class="form-label">State / Province</label>
                <input type="text" class="form-control" id="state" name="state" required value="{{ old('state') }}">
              </div>
              <div class="col-md-6">
                <label for="postal" class="form-label">Postal code</label>
                <input type="text" class="form-control" id="postal" name="postal_code" required value="{{ old('postal_code') }}">
              </div>
            </div>
          </div>
          
          <!-- Delivery method -->
          <div class="mb-4">
            <h3 class="mb-3">Delivery method</h3>
            <div class="row">
              <div class="col-md-6 mb-3 mb-md-0">
                <div class="delivery-option selected">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="delivery" id="standard" value="standard" data-shipping="5.00" checked>
                    <label class="form-check-label w-100" for="standard">
                      <div>Standard</div>
                      <div class="text-muted small">4-10 business days</div>
                      <div class="mt-2 fw-semibold">$5.00</div>
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="delivery-option">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="delivery" id="express" value="express" data-shipping="16.00">
                    <label class="form-check-label w-100" for="express">
                      <div>Express</div>
                      <div class="text-muted small">2-5 business days</div>
                      <div class="mt-2 fw-semibold">$16.00</div>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Payment -->
          <div class="mb-4">
            <h3 class="mb-3">Payment</h3>
            <div class="mb-3">
              <div class="payment-method">
                <input class="form-check-input" type="radio" name="payment" id="creditCard" value="creditCard" checked>
                <label class="form-check-label ms-2" for="creditCard">Credit card</label>
              </div>
              <div class="payment-method">
                <input class="form-check-input" type="radio" name="payment" id="paypal" value="paypal">
                <label class="form-check-label ms-2" for="paypal">PayPal</label>
              </div>
              <div class="payment-method">
                <input class="form-check-input" type="radio" name="payment" id="transfer" value="transfer">
                <label class="form-check-label ms-2" for="transfer">Transfer</label>
              </div>
            </div>
            
            <!-- Credit card fields -->
            <div id="credit-card-fields">
              <div class="mb-3">
                <label for="cardNumber" class="form-label">Card number</label>
                <input type="text" class="form-control" id="cardNumber" name="card_number">
              </div>
              
              <div class="mb-3">
                <label for="nameOnCard" class="form-label">Name on card</label>
                <input type="text" class="form-control" id="nameOnCard" name="card_name">
              </div>
              
              <div class="row">
                <div class="col-md-8 mb-3 mb-md-0">
                  <label for="expiration" class="form-label">Expiration date (MM/YY)</label>
                  <input type="text" class="form-control" id="expiration" name="card_expiry" placeholder="MM/YY">
                </div>
                <div class="col-md-4">
                  <label for="cvc" class="form-label">CVC</label>
                  <input type="text" class="form-control" id="cvc" name="card_cvc">
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Right column - Order Summary -->
        <div class="col-lg-5 order-summary">
          <div class="mb-4 position-sticky top-0">
            <h1 class="mb-3">Order summary</h1>
            <div class="summary-section">
              <!-- Products -->
              @if(isset($cart) && count($cart->items) > 0)
                @foreach($cart->items as $item)
                  <div class="product-card d-flex mb-3" id="product-{{ $item->cart_item_id }}">
                    <div class="card-img-container me-3">
                      @if($item->variant->product->image_url)
                        <img src="{{ asset($item->variant->product->image_url) }}" alt="{{ $item->variant->product->name }}">
                      @else
                        <div class="bg-light" style="width: 80px; height: 80px;"></div>
                      @endif
                    </div>
                    <div class="flex-grow-1">
                      <div class="d-flex justify-content-between align-items-start">
                        <div>
                          <h5 class="mb-0">{{ $item->variant->product->name }}</h5>
                          <div class="product-info">{{ $item->variant->color }}</div>
                          <div class="product-info">{{ $item->variant->size }}</div>
                        </div>
                        <div class="text-end">
                          <div>${{ number_format($item->variant->product->price, 2) }}</div>
                          <div class="mt-2 d-flex align-items-center">
                            <input type="number" min="1" value="{{ $item->quantity }}" class="quantity-selector me-2" readonly>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              @else
                <div class="alert alert-info">
                  Your cart is empty. <a href="{{ url('/CategoryPage') }}">Continue shopping</a>
                </div>
              @endif
              
              <!-- Order totals -->
              <div class="mt-3" id="order-totals">
                <div class="summary-row">
                  <span>Subtotal</span>
                  <span id="subtotal">
                    ${{ number_format($cart->items->sum(function($item) { 
                      return $item->variant->product->price * $item->quantity; 
                    }), 2) }}
                  </span>
                </div>
                <div class="summary-row">
                  <span>Shipping</span>
                  <span id="shipping">$5.00</span>
                </div>
                <div class="summary-row">
                  <span>Taxes</span>
                  <span id="taxes">
                    ${{ number_format($cart->items->sum(function($item) { 
                      return $item->variant->product->price * $item->quantity * 0.1; 
                    }), 2) }}
                  </span>
                </div>
                <div class="summary-row summary-total">
                  <span>Total</span>
                  <span id="total">
                    ${{ number_format(
                      $cart->items->sum(function($item) { 
                        return $item->variant->product->price * $item->quantity; 
                      }) + 5.00 + 
                      $cart->items->sum(function($item) { 
                        return $item->variant->product->price * $item->quantity * 0.1; 
                      }),
                      2) 
                    }}
                  </span>
                </div>
              </div>
              
              <div class="mt-3">
                <button type="submit" class="btn btn-primary confirm-btn w-100 py-2">Confirm order</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

  @include('partials/footer')
  

<script>
  document.querySelectorAll('input[name="delivery"]').forEach(radio => {
    radio.addEventListener('change', function() {
      const shipping = parseFloat(this.getAttribute('data-shipping'));
      const subtotal = parseFloat(document.getElementById('subtotal').textContent.replace('$',''));
      const taxes = subtotal * 0.1;
      const total = subtotal + taxes + shipping;

      document.getElementById('shipping').textContent = `$${shipping.toFixed(2)}`;
      document.getElementById('taxes').textContent = `$${taxes.toFixed(2)}`;
      document.getElementById('total').textContent = `$${total.toFixed(2)}`;
    });
  });
  
  document.querySelectorAll('input[name="payment"]').forEach(radio => {
    radio.addEventListener('change', function() {
      const creditCardFields = document.getElementById('credit-card-fields');
      if (this.value === 'creditCard') {
        creditCardFields.style.display = 'block';
      } else {
        creditCardFields.style.display = 'none';
      }
    });
  });
</script>


</body>

</html>