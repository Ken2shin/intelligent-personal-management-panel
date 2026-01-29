<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailService
{
    /**
     * Enviar correo de recuperación de contraseña
     * Con manejo robusto de errores y fallback
     */
    public static function sendPasswordReset($email, $resetLink)
    {
        try {
            // Intentar enviar via SMTP
            Mail::raw(
                view('emails.password-reset', ['resetLink' => $resetLink])->render(),
                function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Recuperación de Contraseña - Panel Inteligente Seguro');
                }
            );

            Log::info("Reset password email sent to: {$email}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send reset password email to {$email}: " . $e->getMessage());
            
            // Fallback: guardar en log para revisar manualmente
            Log::warning("Password reset link for {$email}: {$resetLink}");
            
            return false;
        }
    }

    /**
     * Enviar notificación de transacción
     */
    public static function sendTransactionNotification($email, $data)
    {
        try {
            Mail::raw(
                "Se ha registrado una transacción: {$data['descripcion']} por \${$data['monto']}",
                function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Nueva Transacción Registrada');
                }
            );

            Log::info("Transaction notification sent to: {$email}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send transaction notification: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Enviar recordatorio de tarea
     */
    public static function sendTaskReminder($email, $taskTitle, $dueDate)
    {
        try {
            Mail::raw(
                "Recordatorio: Tienes una tarea pendiente: {$taskTitle} con vencimiento el {$dueDate}",
                function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Recordatorio de Tarea Pendiente');
                }
            );

            Log::info("Task reminder sent to: {$email}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send task reminder: " . $e->getMessage());
            return false;
        }
    }
}
