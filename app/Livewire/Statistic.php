<?php

namespace App\Livewire;

use App\Models\Location\Province;
use App\Models\Talent;
use App\Services\ProvinceService;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Statistic extends Component
{
    public $provinces; // The number of talent for each province

    public function mount(ProvinceService $provinceService): void
    {
        $this->provinces = $provinceService->get3ProvincesStatistic();
    }

    public function render()
    {
        return view('livewire.statistic');
    }

    public $list = [];
    public function setProvinceId($provinceId = 0)
    {
        // Your data fetching logic
        $this->list = app(ProvinceService::class)->getProvinceTalents($provinceId) ;
        Debugbar::info($this->list);
    }
}
