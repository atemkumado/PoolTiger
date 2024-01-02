<?php

namespace App\Services;

use App\Http\Requests\TalentRequest;
use App\Models\Location\Province;
use App\Models\Position;
use App\Models\Skill;
use App\Models\Talent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Service
{
    protected array $credentials;
    const KEY_VIEW_CACHE = 'VIEW_CACHE';
    const KEY_TOKEN_CACHE = 'TOKEN_CACHE'; // SESSION_TOKEN_CACHE
    const KEY_ACCESS_CACHE = 'ACCESS_CACHE'; // hashed access key
    const KEY_SESSION_NAME_CACHE = 'SESSION_NAME_CACHE'; // hashed session name
    const KEY_USER_ID_CACHE = 'USER_ID_CACHE'; // hashed user

    public function __construct()
    {
        Log::debug("SERVICE");
        $this->credentials = config('services.crm');
    }


    public function storeData($value, $key = self::KEY_VIEW_CACHE): void
    {
        Cache::put($key, $value, now()->addHours());
    }

    public function getData($key = self::KEY_VIEW_CACHE)
    {
        Log::debug("GET DATA FROM CATCH WITH KEY: ".$key);

        return Cache::get($key) ?? false;
    }
}
