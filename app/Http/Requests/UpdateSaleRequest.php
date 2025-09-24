<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        // ganti ke policy kalau ada: return $this->user()->can('update', $this->route('sale'));
        return true;
    }

    protected function prepareForValidation(): void
    {
        $premium = (string) $this->input('premium', '');
        $premium = str_replace(['.', ' '], '', $premium);
        $premium = str_replace(',', '.', $premium);

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

            // cegah manipulasi status/approval dari form edit biasa
            'status'        => ['prohibited'],
            'approved_by'   => ['prohibited'],
            'approved_at'   => ['prohibited'],
            'approval_note' => ['prohibited'],
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
}
