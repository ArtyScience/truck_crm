<?php

namespace Modules\Core\View;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DynamicHeader extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $title
    ){}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('core::components.dynamic-header');
    }
}
