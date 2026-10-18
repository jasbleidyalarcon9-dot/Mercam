<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            // "current_password" valida contra el hash guardado del usuario
            // autenticado: la contraseña sigue 100% gestionada por el
            // sistema de cuentas de Laravel, nunca a mano en el controlador.
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.current_password' => 'La contraseña actual no es correcta.',
        ];
    }
}
