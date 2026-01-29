<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 5px 5px 0 0; }
        .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
        .footer { background: #333; color: white; padding: 10px; text-align: center; border-radius: 0 0 5px 5px; }
        .button { display: inline-block; background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .warning { background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 5px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Panel Inteligente Seguro</h1>
        </div>

        <div class="content">
            <h2>Solicitud de Recuperación de Contraseña</h2>
            
            <p>Hemos recibido una solicitud para restablecer tu contraseña. Si no fuiste tú, por favor ignora este correo.</p>

            <p>Para restablecer tu contraseña, haz clic en el botón a continuación:</p>

            <div style="text-align: center;">
                <a href="{{ $resetLink }}" class="button">Restablecer Contraseña</a>
            </div>

            <p>O copia y pega este enlace en tu navegador:</p>
            <p><small>{{ $resetLink }}</small></p>

            <div class="warning">
                <strong>⚠️ Información importante:</strong>
                <ul>
                    <li>Este enlace expira en 60 minutos</li>
                    <li>Nunca compartas este enlace con terceros</li>
                    <li>Si no solicitaste cambiar tu contraseña, tu cuenta está segura</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Panel Inteligente Seguro. Todos los derechos reservados.</p>
            <p>Este es un correo automático, por favor no responder.</p>
        </div>
    </div>
</body>
</html>
