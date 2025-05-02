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
    public $seriesName;
    public $pendingSeries;
    public $approvedSeries;
    public $rejectedSeries;
    public function __construct($chartId, $title, $subtitle, $categories, $series,$chartType,$seriesName='',$rejectedSeries=[],$approvedSeries=[],$pendingSeries=[])
    {
        $this->chartId = $chartId;
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->categories = $categories;
        $this->series = $series;
        $this->chartType = $chartType;
        $this->seriesName = $seriesName;  
        $this->pendingSeries = $pendingSeries;
        $this->approvedSeries = $approvedSeries;
        $this->rejectedSeries = $rejectedSeries;
    }

    public function render()
    {
        return view('components.highchart');
    }
}
