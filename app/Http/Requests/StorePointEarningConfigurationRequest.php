<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePointEarningConfigurationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'min_purchase_amount' => 'required|numeric|min:0',
            'max_purchase_amount' => 'nullable|numeric|min:0',
            'points_earned' => 'required|integer|min:1',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'min_purchase_amount.required' => 'Minimum nominal pembelanjaan harus diisi',
            'min_purchase_amount.numeric' => 'Minimum nominal harus berupa angka',
            'points_earned.required' => 'Poin yang diperoleh harus diisi',
            'points_earned.min' => 'Poin harus minimal 1',
        ];
    }
}
