<div class="sidebar">
    <form action="{{ route('category.index') }}" method="GET" id="filter-form-desktop">
        <x-filter-section :heading="'Price'" :options="[]" />
        <x-filter-section :heading="'Category'" :options="$categoryOptions" />
        <x-filter-section :heading="'Color'" :options="$colorOptions" />
        <x-filter-section :heading="'Sizes'" :options="$sizeOptions" />
        
        @foreach(request()->except(['color', 'category', 'sizes', 'price_min', 'price_max', 'page']) as $key => $value)
            @if(is_array($value))
                @foreach($value as $item)
                    <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                @endforeach
            @else
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach
        
        <button type="submit" class="btn btn-primary mt-3">Apply Filters</button>
        
        @if(request()->has('color') || request()->has('category') || request()->has('sizes') || request()->has('price_min') || request()->has('price_max'))
            <a href="{{ route('category.index') }}" class="btn btn-outline-secondary mt-2">Clear Filters</a>
        @endif
    </form>
</div>