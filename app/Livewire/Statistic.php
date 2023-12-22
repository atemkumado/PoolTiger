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


    public function mount(): void
    {
        $this->provinces = Province::get3ProvincesStatistic();
    }

    public function render()
    {
        return view('livewire.statistic');
    }

    public $provinceId;
    public function setProvinceId($provinceId = 0)
    {
        $this->provinceId = $provinceId;
        $this->dispatch('getProvinceId', $provinceId);

    }
}
