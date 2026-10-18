<?php

namespace App\Notifications;

use App\Models\DetallePedido;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class EstadoPedidoActualizado extends Notification implements ShouldQueue
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
            'titulo' => 'Tu pedido cambió de estado',
            'mensaje' => "\"{$this->detalle->post->title}\" ahora está: ".ucfirst($this->detalle->estado),
            'pedido_id' => $this->detalle->pedido_id,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Mercampo — actualización de tu pedido')
            ->greeting('¡Hola, '.$notifiable->name.'!')
            ->line("Tu producto \"{$this->detalle->post->title}\" del pedido #{$this->detalle->pedido_id} ahora está: ".ucfirst($this->detalle->estado).'.')
            ->action('Ver mi pedido', route('pedidos.show', $this->detalle->pedido_id));
    }
}
