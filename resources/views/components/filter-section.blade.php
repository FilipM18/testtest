<div class="filter-section">
    <h6 class="category-heading d-flex justify-content-between align-items-center" 
        data-bs-toggle="collapse" 
        data-bs-target="#collapse{{ str_replace(' ', '', $heading) }}" 
        aria-expanded="false" 
        aria-controls="collapse{{ str_replace(' ', '', $heading) }}"
        style="cursor: pointer;">
        {{ $heading }}
        <i class="bi bi-chevron-down"></i>
    </h6>
    
    <div class="collapse" id="collapse{{ str_replace(' ', '', $heading) }}">
        @if($heading == 'Price')
            <div class="price-range mb-3">
                <div class="d-flex justify-content-between mb-2">
                </div>
                <div class="d-flex">
                    <input type="number" 
                           class="form-control form-control-sm me-2" 
                           id="min-price" 
                           name="price_min" 
                           placeholder="Min $" 
                           min="0" 
                           value="{{ request('price_min') }}">
                    <input type="number" 
                           class="form-control form-control-sm" 
                           id="max-price" 
                           name="price_max" 
                           placeholder="Max $" 
                           min="0" 
                           value="{{ request('price_max') }}">
                </div>
            </div>
        @else
            @foreach($options as $option)
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" 
                           name="{{ strtolower($heading) }}[]" 
                           value="{{ $option['value'] }}" 
                           id="{{ $option['id'] }}"
                           {{ request()->has(strtolower($heading)) && in_array($option['value'], (array)request(strtolower($heading))) ? 'checked' : '' }}>
                    <label class="form-check-label" for="{{ $option['id'] }}">{{ $option['label'] }}</label>
                </div>
            @endforeach
        @endif
    </div>
</div>