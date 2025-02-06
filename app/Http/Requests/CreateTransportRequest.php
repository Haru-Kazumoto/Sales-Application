<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTransportRequest extends FormRequest
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
            'code' => 'required|string',
            'name' => 'required|string',
            'pic' => 'required|string',
            'pic_2' => 'nullable|string',
            'phone' => 'nullable|string',
            'phone_2' => 'nullable|string',
            'address' => 'nullable|string',
        ];
    }
}
