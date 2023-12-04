<?php

namespace App\Livewire;

use App\Models\Talent;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridColumns;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class Talents extends PowerGridComponent
{
    use WithExport;

    public $data;

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

    public function datasource(): ?Collection
    {
        return new Collection($this->data || []);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('no')
            ->addColumn('avatarUrl')
            ->addColumn('name')
            ->addColumn('email')
            ->addColumn('phone')
            ->addColumn('province')
            ->addColumn('company');
//            ->addColumn('created_at_formatted', fn(Talent $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }

    public function columns(): array
    {
        return [

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
            Column::add()
                ->title(__('COMPANY'))
                ->field('company_id')
                ->sortable(),
            Column::add()
                ->title(__('YEARS'))
                ->field('experience')
                ->sortable(),
            Column::add()
                ->title(__('PROVINCE'))
                ->field('province_id')
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
