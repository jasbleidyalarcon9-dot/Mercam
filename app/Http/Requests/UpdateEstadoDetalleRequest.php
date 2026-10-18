<?php

namespace App\Http\Requests;

use App\Models\DetallePedido;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEstadoDetalleRequest extends FormRequest
{
    public function authorize(): bool
    {
        // La verificación de "es tu producto" la hace DetallePedidoPolicy,
        // llamada explícitamente en el controlador.
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'estado' => ['required', 'in:'.implode(',', DetallePedido::ESTADOS)],
        ];
    }
}
