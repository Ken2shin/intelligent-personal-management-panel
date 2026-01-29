<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword implements ShouldQueue
{
    use Queueable;

    public function __construct($token)
    {
        parent::__construct($token);
        $this->afterCommit();
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Recuperación de Contraseña - Panel Inteligente Seguro')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Hemos recibido una solicitud para restablecer tu contraseña.')
            ->action('Restablecer Contraseña', $resetUrl)
            ->line('Este enlace de restablecimiento de contraseña expirará en ' . config('auth.passwords.users.expire') . ' minutos.')
            ->line('Si no solicitaste el restablecimiento de contraseña, simplemente ignora este correo.')
            ->salutation('Saludos,<br>Panel Inteligente Seguro');
    }
}
