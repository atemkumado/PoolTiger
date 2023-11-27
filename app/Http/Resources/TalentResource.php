<?php

namespace App\Http\Resources;

use App\Enums\EnglishLevel;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TalentResource extends JsonResource
{
    public bool $preserveKeys = true;
    public function toArray($request): array
    {
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
            'position_id' => $this->position[0]['id'] ?? $this->position,
            'position' => PositionResource::collection($this->whenLoaded('position')),
            'position_name' => $this->position[0]['name'] ?? $this->position,
            'skill_id' => $this->skill[0]['id'] ?? $this->skill,
            'skill_name' => $this->skill[0]['name'] ?? $this->skill,
            'skill' => SkillResource::collection($this->whenLoaded('skill')),
            'english' => EnglishLevel::cases(),
            'company_id' => $this->company_id,
            'company_name' => $this->company['name'] ?? $this->company,
        ];
    }
}
