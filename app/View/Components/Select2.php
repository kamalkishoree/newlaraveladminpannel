<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Select2 extends Component
{
    public $name;
    public $id;
    public $options;
    public $selected;
    public $multiple;
    public $placeholder;

    public function __construct($name, $id, $options = [], $selected = null, $multiple = false, $placeholder = 'Select an option')
    {
        $this->name = $name;
        $this->id = $id;
        $this->options = $options;
        $this->selected = $selected;
        $this->multiple = $multiple;
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        return view('components.select2');
    }
}
