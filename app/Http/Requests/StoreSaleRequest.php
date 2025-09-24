<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        // ganti ke policy kalau sudah ada: return $this->user()->can('create', Sale::class);
        return true;
    }

    protected function prepareForValidation(): void
    {
        $premium = (string) $this->input('premium', '');

        // normalisasi: "1.234.567,89" -> "1234567.89"
        $premium = str_replace(['.', ' '], '', $premium); // buang titik ribuan & spasi
        $premium = str_replace(',', '.', $premium);       // koma -> titik

        $this->merge([
            'customer_name' => trim((string) $this->input('customer_name', '')),
            'premium'       => $premium,
        ]);
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:150'],
            'sale_date'     => ['required', 'date', 'before_or_equal:today'],
            'product_id'    => ['required', 'exists:products,id'],
            'case_level'    => ['required', 'integer', 'in:1,2,3'],
            'premium'       => ['required', 'numeric', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'customer_name' => 'nama nasabah',
            'sale_date'     => 'tanggal penjualan',
            'product_id'    => 'produk',
            'case_level'    => 'case',
            'premium'       => 'premi',
        ];
    }

    public function messages(): array
    {
        return [
            'sale_date.before_or_equal' => 'Tanggal penjualan tidak boleh melebihi hari ini.',
            'premium.min'               => 'Premi tidak boleh negatif.',
        ];
    }
}
