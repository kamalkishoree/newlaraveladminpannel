<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Ckeditor extends Component
{
    public $name;
    public $id;
    public $label;
    public $value;
    public $placeholder;

    public function __construct($name, $id, $label = 'Content', $value = '', $placeholder = 'Enter text...')
    {
        $this->name = $name;
        $this->id = $id;
        $this->label = $label;
        $this->value = $value;
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        return view('components.ckeditor');
    }
}
