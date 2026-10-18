<?php
 
namespace App\Providers;
 
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Illuminate\Support\ServiceProvider;
 
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
 
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        URL::forceScheme('https');

        // Transporte de correo "brevo" (API HTTPS). Railway bloquea el SMTP
        // (puertos 25/465/587) en su plan Hobby, así que en la nube los correos
        // salen por la API de Brevo. Si la librería no está instalada, no se
        // registra y el resto de la app sigue funcionando con SMTP o log.
        if (class_exists(BrevoTransportFactory::class)) {
            Mail::extend('brevo', fn () => (new BrevoTransportFactory)->create(
                new Dsn('brevo+api', 'default', config('services.brevo.key'))
            ));
        }
        // El correo de "confirma tu cuenta" viene en inglés por defecto;
        // lo personalizamos aquí en vez de depender de archivos de idioma
        // para ESTE mensaje puntual.
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Mercam — confirma tu correo')
                ->greeting('¡Hola, '.$notifiable->name.'!')
                ->line('Gracias por registrarte en Mercampo. Confirma tu correo electrónico haciendo clic en el siguiente botón.')
                ->action('Confirmar correo', $url)
                ->line('Si tú no creaste esta cuenta, puedes ignorar este mensaje.');
        });
    }
}