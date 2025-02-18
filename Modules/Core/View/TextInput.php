<?php

namespace Modules\Core\View;

use Illuminate\View\Component;
use Illuminate\View\View;

class TextInput extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('core::components.text-input');
    }
}
