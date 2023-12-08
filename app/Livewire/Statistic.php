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
    public $provinces ;
    public function __construct()
    {
        $this->provinces = Province::get3ProvincesStatistic();
    }

    public function render()
    {

        Debugbar::info($this->provinces);
        return view('livewire.statistic',[
            'provinces' => $this->provinces
        ])->layout('index');
    }

    public $provinceId;
    public function setProvinceId($id)
    {
        $this->provinceId = $id;
        $this->dispatch('provinceId', $id);
    }

}
