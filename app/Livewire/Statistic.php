<?php

namespace App\Livewire;

use App\Models\Location\Province;
use App\Models\Talent;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Statistic extends Component
{
    public $provinces; // The number of talent for each province

    public function loadData()
    {
        // Emit a Livewire event to indicate data loading has started
        $this->emit('loadingData');

        // Your data fetching logic
        $data = YourModel::all(); // Example data fetching, replace with your logic

        // Emit a Livewire event to indicate data loading has ended
        $this->emit('dataLoaded', $data);
    }
    public function mount(): void
    {
        $this->provinces = Province::get3ProvincesStatistic();
    }

    public function render()
    {
        return view('livewire.statistic');
    }

    public $loading = false;
    public $list = [];
    public function setProvinceId($provinceId = 0)
    {
        // Your data fetching logic
        $this->list = Province::getProvinceTalents($provinceId) ;
        Debugbar::info($this->list);
        // Emit a Livewire event to indicate data loading has ended




    }
}
