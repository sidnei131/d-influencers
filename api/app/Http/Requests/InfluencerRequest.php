<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InfluencerRequest extends FormRequest
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
            'name' => 'required|string|max:45',
            'ig_user' => 'required|string|max:45|unique:influencers,ig_user',
            'followers' => 'required|integer|min:0',
            'category' => 'required|string|max:45',
            'campaigns' => 'nullable|array',
            'campaigns.*' => 'exists:campaigns,id',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $influencerId = $this->route('id');
            $rules['name'] = 'sometimes|required|string|max:45';
            $rules['ig_user'] = 'sometimes|required|string|max:45|unique:influencers,ig_user,' . $influencerId;
            $rules['followers'] = 'sometimes|required|integer|min:0';
            $rules['category'] = 'sometimes|required|string|max:45';
        }

        return $rules;
    }
}
