<?php

namespace Modules\Core\View;

use Illuminate\View\Component;
use Illuminate\View\View;
use Modules\Core\View\Closure;

class Modal extends Component
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
    public function render(): \Illuminate\Contracts\View\View|Closure|string
    {
        return view('core::components.modal');
    }
}
