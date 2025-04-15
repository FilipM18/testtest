<div class="filter-section">
    <h6 class="category-heading">{{ $heading }}</h6>
    @foreach($options as $option)
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" value="{{ $option['value'] }}" id="{{ $option['id'] }}{{ $mobile ?? '' }}">
            <label class="form-check-label" for="{{ $option['id'] }}{{ $mobile ?? '' }}">{{ $option['label'] }}</label>
        </div>
    @endforeach
</div>
