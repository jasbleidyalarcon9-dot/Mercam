<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        // La ruta ya está detrás de 'role:administrador'; esto es una
        // segunda barrera por si el FormRequest se reutiliza en otro lado.
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'role_id' => ['required', 'exists:roles,id'],
        ];
    }
}
