<?php

namespace App\Livewire;

use App\Models\Talent;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class TalentsProvince extends PowerGridComponent
{
    use WithExport;

    public $data;
    public $provinceId;

    public function template(): ?string
    {
        return \App\PowerGridThemes\GridData::class;
    }

    public function construct(){

    }
    public function setUp(): array
    {
//        $this->showCheckBox();

        return [
//            Exportable::make('export')
//                ->striped()
//                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
//            Header::make()->showSearchInput(),
//            Footer::make()
//                ->showPerPage()
//                ->showRecordCount(),
        ];
    }
    public function setDataByProvince()
    {
        $provinceId = $this->provinceId;
        Debugbar::info($provinceId);

        $this->data = Talent::whereHas('skill', function ($query) use ($provinceId) {
            if (is_null($provinceId)) {
              return null;
            }
            $query->where('province_id', $provinceId);
        })
//            ->with(['company:id,name,province_id', 'company.province'])
            ->with(['company:id,name,province_id', 'company.province', 'position:id,name', 'province:id,name', 'skill' => function ($query) {
                $query->where('is_best', true)->get(['skills.id', 'skills.name']);
            }])->get()
            ->map(function ($talent) {
                $talent->province_name = @$talent->province['name'];
                $talent->skill_name = @$talent->skill[0]['name'];
                $talent->position_name = @$talent->position[0]['name'];
                return $talent;
            });
    }
    public function datasource(): ?Collection
    {
//        dd($this->data);
        $this->setDataByProvince();
        return new Collection($this->data ?? []);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function columns(): array
    {
        return [
            Column::add()
                ->title('No')
                ->field('no')
                ->index(),
            Column::add()
                ->title(__('AVATAR'))
                ->field('avatarUrl'),
            Column::add()
                ->title(__('NAME'))
                ->field('name')
                ->sortable(),
            Column::add()
                ->title(__('EMAIL'))
                ->field('email')
                ->sortable(),
            Column::add()
                ->title(__('PHONE'))
                ->field('phone')
                ->sortable(),
            Column::make(
                title: __('COMPANY'),
                field: 'company_name',
            ),
            Column::add()
                ->title(__('PROVINCE'))
                ->field('province_name')
                ->sortable(),
            Column::add()
                ->title(__('YEARS'))
                ->field('experience')
                ->sortable(),
            Column::add()
                ->title(__('SKILL'))
                ->field('skill_name')
                ->sortable(),
            Column::add()
                ->title(__('POSITION'))
                ->field('position_name')
                ->sortable(),


//            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datetimepicker('created_at'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

//    public function actions(\App\Models\Talent $row): array
//    {
//        return [
//            Button::add('edit')
//                ->slot('Edit: ' . $row->id)
//                ->id()
//                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
//                ->dispatch('edit', ['rowId' => $row->id])
//        ];
//    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
