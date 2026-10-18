<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        // La verificación real de "es el dueño" la hace PostPolicy::update,
        // llamada explícitamente en el controlador con $this->authorize().
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $post = $this->route('post');

        return [
            'title'     => ['required', 'string', 'max:150'],
            'slug'      => ['nullable', 'string', 'max:150', 'alpha_dash', Rule::unique('posts', 'slug')->ignore($post?->id)],
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
