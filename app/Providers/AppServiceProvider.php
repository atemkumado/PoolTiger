<?php

namespace App\Providers;

use App\Models\Location\Province;
use App\Models\Location\Ward;
use App\Models\Position;
use App\Models\Skill;
use App\Models\Talent;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

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
        //
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
