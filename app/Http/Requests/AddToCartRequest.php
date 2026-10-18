<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        // El máximo real contra el stock se valida aparte en el controlador,
        // porque el stock puede cambiar entre que se carga el form y se envía.
        return [
            'cantidad' => ['required', 'integer', 'min:1', 'max:1000'],
        ];
    }
}
