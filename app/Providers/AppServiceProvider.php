<?php

namespace App\Providers;

use App\Livewire\Statistic;
use App\Models\Location\Province;
use App\Models\Location\Ward;
use App\Models\Position;
use App\Models\Skill;
use App\Models\Talent;
use App\Services\ProvinceService;
use App\Services\TalentService;
use App\Services\VtigerService;
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
        $services = [
            'TalentService' => TalentService::class,
            'ProvinceService' => ProvinceService::class,
            'VtigerService' => VtigerService::class,
            // Add more services as needed
        ];

        foreach ($services as $alias => $service) {
            $this->app->singleton($alias, function ($app) use ($service) {
                return new $service();
            });
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share the talent data for selected inputs
        $filter = @TalentService::getFilter();
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
