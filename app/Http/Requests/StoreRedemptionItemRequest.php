<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRedemptionItemRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'points_cost' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'max_redemption_per_user' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama item harus diisi',
            'points_cost.required' => 'Harga poin harus diisi',
            'points_cost.min' => 'Harga poin minimal 1',
            'stock.required' => 'Stok harus diisi',
            'max_redemption_per_user.required' => 'Max redemption harus diisi',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ];
    }
}
