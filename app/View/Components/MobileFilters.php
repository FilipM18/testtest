<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MobileFilters extends Component
{
    public $colorOptions, $categoryOptions, $sizeOptions;

    public function __construct($colorOptions, $categoryOptions, $sizeOptions)
{
    $this->colorOptions = $colorOptions;
    $this->categoryOptions = $categoryOptions;
    $this->sizeOptions = $sizeOptions;
}

    public function render(): View|Closure|string
    {
        return view('components.mobile-filters');
    }
}
