<?php

namespace App\Livewire;

use App\Models\Talent;
use Illuminate\Support\Collection;
use Livewire\Component;

class Statistic extends Component
{
    public Collection|null $provinceData;

    public function render()
    {
        return view('livewire.statistic')->layout('index');
    }


    public function closeModal()
    {

    }

}
