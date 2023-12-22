<?php

namespace App\Providers;

use App\Http\Livewire\YourComponent;
use App\Livewire\Statistic;
use App\Models\Location\Province;
use App\Models\Location\Ward;
use App\Models\Position;
use App\Models\Skill;
use App\Models\Talent;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share the talent data for selected inputs
        $filter = @Talent::getFilter();
        // Share the talent data for selected inputs
        View::share(compact('filter'));
        // Share the talent following province information

        Relation::enforceMorphMap([
            'talent' => Talent::class,
            'position' => Position::class,
            'skill' => Skill::class,
            'province' => Province::class,
            'ward' => Ward::class,
        ]);
        JsonResource::withoutWrapping();
    }
}
