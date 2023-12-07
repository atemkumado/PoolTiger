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
    protected array $provinces;
    protected array $skills;
    protected array $position;
    public function __construct()
    {
        $this->provinces = @Province::all()->pluck('name', 'id')->toArray() ?? [];
        $this->skills = @Skill::all()->pluck('name', 'id')->toArray() ?? [];
        $this->position = @Position::all()->pluck('name', 'id')->toArray() ?? [];
    }

}
