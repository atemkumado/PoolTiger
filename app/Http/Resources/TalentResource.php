<?php

namespace App\Http\Resources;

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
            'avatar_name' => $this->avatar_name,
            'avatar_url' => $this->avatar_url,
            'province_id' => $this->province_id,
            'province_name' => $this->province['name'] ?? $this->province,
            'experience' => $this->experience,
            'position_id' => $this->position_id,
            'position_name' => $this->position[0]['name'] ?? $this->position,
            'skill_id' => $this->skill_id,
            'skill_name' => $this->skill[0]['name'] ?? $this->skill,
            'english' => $this->english,
            'company_id' => $this->company_id,
            'company_name' => $this->company['name'] ?? $this->company,
        ];
    }
}
