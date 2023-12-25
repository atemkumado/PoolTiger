<?php

namespace App\Http\Controllers;

use App\Models\Location\Province;
use App\Models\Position;
use App\Models\Skill;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function combine(...$arrays): array|\Illuminate\Support\Collection
    {
        return collect($arrays)->flatten(1) ?? [];
    }
}
