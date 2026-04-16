<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'address_id' => 'required|exists:addresses,id',
            
            // Mahsulotlar massivini tekshirish
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'address_id.required' => 'Yetkazib berish manzili tanlanishi shart.',
            'items.required' => 'Savatchangiz bo\'sh bo\'lmasligi kerak.',
            'items.*.product_id.exists' => 'Tanlangan mahsulot tizimda mavjud emas.',
            'items.*.quantity.min' => 'Mahsulot miqdori kamida 1 ta bo\'lishi kerak.',
        ];
    }
}