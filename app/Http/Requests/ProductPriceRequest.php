<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductPriceRequest extends FormRequest
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
        return [
            'redemp_price' => 'required|numeric',
            'retail_price' => 'nullable|numeric',
            'grosir_price' => 'nullable|numeric',
            'end_user_price' => 'nullable|numeric',
            'all_segment_price' => 'nullable|numeric',
            'percentage' => 'nullable|numeric',
            'transportation_cost' => 'nullable|numeric',
            'oh_depo' => 'nullable|numeric',
            'budget_marketing' => 'nullable|numeric',
            'bad_debt' => 'nullable|numeric',
            'saving' => 'nullable|numeric',
            'margin_retail' => 'nullable|numeric',
            'margin_grosir' => 'nullable|numeric',
            'margin_end_user' => 'nullable|numeric',
            'margin_all_segment' => 'nullable|numeric',
            'rounded_all_segment_price' => 'nullable|numeric',
            'product_id' => 'required|numeric'
        ];
    }
}
