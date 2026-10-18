<?php
 
namespace App\Http\Requests\Auth;
 
use App\Rules\DominioDeCorreoValido;
use Illuminate\Foundation\Http\FormRequest;
 
class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Cualquier visitante puede intentar registrarse.
        return true;
    }
 
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email', new DominioDeCorreoValido()],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // el usuario elige si vende o compra; el admin NUNCA se asigna aquí
            'tipo_cuenta' => ['required', 'in:productor,consumidor'],
            // Ley 1581 de 2012 (habeas data): sin aceptar, no hay registro
            'acepta_tratamiento_datos' => ['required', 'accepted'],
        ];
    }
 
    public function messages(): array
    {
        return [
            'email.unique' => 'Ya existe una cuenta registrada con ese correo.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'tipo_cuenta.in' => 'Selecciona si eres productor o consumidor.',
            'acepta_tratamiento_datos.accepted' => 'Debes aceptar la política de tratamiento de datos para registrarte.',
        ];
    }
}