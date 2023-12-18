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
//        $this->dispatch('setProvince', serialize($this->provinceTalents));

//        Debugbar::info($this->provinceTalents);
    }

    public function render()
    {
//        Debugbar::info($this->provinceTalents);

        $data = [];
//        foreach ($this->provinceTalents as $key => $value) {
//            $data[$key] = count($value);
//        }
        return view('livewire.statistic', [
            'provinces' => $data
        ])->layout('index');
    }

    public $provinceId;
    public function setDataByProvince($provinceId = 0)
    {
        $this->provinceId = $provinceId;
        $this->dispatch('setProvinceId', $provinceId);

    }
}
