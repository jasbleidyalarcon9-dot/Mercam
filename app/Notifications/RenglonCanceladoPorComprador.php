<?php

namespace App\Notifications;

use App\Models\DetallePedido;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class RenglonCanceladoPorComprador extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public DetallePedido $detalle)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'titulo' => 'Un comprador canceló un pedido',
            'mensaje' => "\"{$this->detalle->post->title}\" fue cancelado por el comprador antes de que lo confirmaras.",
            'pedido_id' => $this->detalle->pedido_id,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Mercampo — un pedido fue cancelado')
            ->line("El comprador canceló su pedido de \"{$this->detalle->post->title}\" (pedido #{$this->detalle->pedido_id}) antes de que lo confirmaras.");
    }
}
