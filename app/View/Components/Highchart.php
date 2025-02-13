<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Highchart extends Component
{
    public $chartId;
    public $title;
    public $subtitle;
    public $categories;
    public $series;
    public $chartType;
    public function __construct($chartId, $title, $subtitle, $categories, $series,$chartType)
    {
        $this->chartId = $chartId;
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->categories = $categories;
        $this->series = $series;
        $this->chartType = $chartType;
    }

    public function render()
    {
        return view('components.highchart');
    }
}
