<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TalentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    
    public function rules(): array
    {
        return [
            'province' => ['required'],
            'skill' => ['required'],
        ];
    }
}
