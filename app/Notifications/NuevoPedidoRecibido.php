<?php

namespace App\Notifications;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class NuevoPedidoRecibido extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param Pedido $pedido
     * @param array<int,array{producto:string,cantidad:int,subtotal:float}> $misRenglones
     *        solo los renglones de ESTE productor dentro del pedido, nunca los de otro
     */
    public function __construct(
        public Pedido $pedido,
        public array $misRenglones,
    ) {
    }

    public function via(object $notifiable): array
    {
        // 'database' funciona sin configurar correo. Cuando tengas MAIL_MAILER
        // en tu .env, agrega 'mail' a este arreglo y ya se envían también por email.
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'titulo' => 'Tienes un pedido nuevo',
            'mensaje' => 'Recibiste un pedido con '.count($this->misRenglones).' producto(s).',
            'pedido_id' => $this->pedido->id,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Mercampo — tienes un pedido nuevo')
            ->greeting('¡Hola, '.$notifiable->name.'!')
            ->line('Acabas de recibir un pedido nuevo en Mercampo:');

        foreach ($this->misRenglones as $renglon) {
            $mail->line("- {$renglon['cantidad']} x {$renglon['producto']} (\${$renglon['subtotal']})");
        }

        return $mail->action('Ver pedido', route('productor.pedidos'))
            ->line('Recuerda coordinar la entrega y el pago directamente con el comprador.');
    }
}
