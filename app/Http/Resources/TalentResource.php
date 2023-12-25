<?php

namespace App\Http\Resources;

use App\Enums\EnglishLevel;
use App\Models\Skill;
use App\Models\Talent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TalentResource extends JsonResource
{
    public bool $preserveKeys = true;
    public function toArray($request): array
    {
        $eng = EnglishLevel::toArray();
//        return $eng;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'salary' => $this->salary,
            'expect' => $this->expect,
            'avatar_name' => $this->avatar_name,
            'avatar_url' => $this->avatar_url,
            'province_id' => $this->province_id,
            'province_name' => $this->province['name'] ?? $this->province,
            'district_id' => $this->district_id,
            'district' => $this->district['name'] ?? $this->district,
            'ward_id' => $this->ward_id,
            'ward' => $this->ward['name'] ?? $this->ward,
            'experience' => $this->experience,
            'position' => PositionResource::collection($this->whenLoaded('position')),
            'skill_id' => $this->skill[0]['id'] ?? $this->skill,
            'skill' => SkillResource::collection($this->whenLoaded('skill')),
            'english' => @Talent::ENGLISH_LEVEL[$this->english],
            'company_id' => $this->company_id,
            'company' => CompanyResource::collection($this->whenLoaded('company')),
        ];
    }
}
