<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'budget' => 'required|numeric|min:0',
            'desc' => 'nullable|string',
            'init_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:init_date',
            'influencers' => 'nullable|array',
            'influencers.*' => 'exists:influencers,id',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['name'] = 'sometimes|required|string|max:255';
            $rules['budget'] = 'sometimes|required|numeric|min:0';
            $rules['desc'] = 'sometimes|nullable|string';
            $rules['init_date'] = 'sometimes|required|date';
            $rules['end_date'] = 'sometimes|required|date|after_or_equal:init_date';
        }

        return $rules;
    }
}
