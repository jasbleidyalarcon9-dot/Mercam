<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Solo productores (y administradores) publican productos en el catálogo.
        $user = $this->user();

        return $user !== null && in_array($user->role?->name, ['productor', 'administrador'], true);
    }

    public function rules(): array
    {
        return [
            'title'     => ['required', 'string', 'max:150'],
            'slug'      => ['nullable', 'string', 'max:150', 'alpha_dash', 'unique:posts,slug'],
            'enlace'    => ['nullable', 'url', 'max:255'],
            'content'   => ['required', 'string', 'max:5000'],
            'status'    => ['required', 'in:draft,published'],
            'precio'    => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'stock'     => ['required', 'integer', 'min:0'],
            'unidad'    => ['required', 'string', 'max:30'],
            'categoria' => ['nullable', 'string', 'max:60'],
            'imagen'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
