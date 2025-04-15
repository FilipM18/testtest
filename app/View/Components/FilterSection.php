<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FilterSection extends Component
{
    public $heading;
    public $options;
    public $mobile;

    public function __construct($heading, $options, $mobile = null)
    {
        $this->heading = $heading;
        $this->options = $options;
        $this->mobile = $mobile;
    }

    public function render()
    {
        return view('components.filter-section');
    }
}
