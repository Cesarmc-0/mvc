<?php
require_once __DIR__ . '/../vendor/autoload.php';

class EmailService {

    private static function client(): \Resend\Client {
        return Resend::client(RESEND_API_KEY);
    }

    public static function enviarBienvenida(string $nombre, string $email): void {
        try {
            self::client()->emails->send([
                'from'    => 'Lumière Hotels <onboarding@resend.dev>',
                'to'      => [$email],
                'subject' => 'Bienvenido a Lumière Hotels',
                'html'    => self::templateBienvenida($nombre),
            ]);
        } catch (\Exception $e) {
            error_log('EmailService::enviarBienvenida - ' . $e->getMessage());
        }
    }

    public static function enviarConfirmacionReserva(array $reserva, string $nombre, string $email): void {
        try {
            self::client()->emails->send([
                'from'    => 'Lumière Hotels <onboarding@resend.dev>',
                'to'      => [$email],
                'subject' => 'Confirmación de Reserva — Lumière Hotels',
                'html'    => self::templateReserva($reserva, $nombre),
            ]);
        } catch (\Exception $e) {
            error_log('EmailService::enviarConfirmacionReserva - ' . $e->getMessage());
        }
    }

    private static function templateBienvenida(string $nombre): string {
        return "
        <div style='font-family:Georgia,serif;max-width:600px;margin:auto;background:#fff;border:1px solid #e8ddc9;'>
            <div style='background:#1a1610;padding:30px;text-align:center;'>
                <h1 style='color:#fff;font-size:28px;font-weight:300;margin:0;letter-spacing:4px;'>LUMIÈRE</h1>
                <p style='color:#b08f5f;font-size:11px;letter-spacing:3px;margin:6px 0 0;'>HOTELS</p>
            </div>
            <div style='padding:40px;'>
                <p style='color:#b08f5f;font-size:11px;letter-spacing:3px;text-transform:uppercase;'>Bienvenido</p>
                <h2 style='color:#1a1610;font-size:24px;font-weight:300;margin:8px 0 20px;'>Hola, {$nombre}</h2>
                <p style='color:#5a4a3a;line-height:1.8;'>
                    Tu cuenta en <strong>Lumière Hotels</strong> ha sido creada exitosamente.
                    Ya puedes explorar nuestras habitaciones y realizar reservas.
                </p>
                <div style='margin:30px 0;text-align:center;'>
                    <a href='" . SITE_URL . "index.php?action=getFormCreateReserva'
                       style='background:#1a1610;color:#fff;padding:14px 36px;text-decoration:none;font-size:12px;letter-spacing:3px;text-transform:uppercase;'>
                        Reservar ahora
                    </a>
                </div>
            </div>
            <div style='background:#f5f1ea;padding:20px;text-align:center;'>
                <p style='color:#8b7355;font-size:11px;margin:0;'>© 2026 Lumière Hotels. Todos los derechos reservados.</p>
            </div>
        </div>";
    }

    private static function templateReserva(array $r, string $nombre): string {
        $fechaInicio = date('d/m/Y', strtotime($r['fecha_inicio']));
        $fechaFin    = date('d/m/Y', strtotime($r['fecha_fin']));
        $precio      = '$' . number_format($r['precio'], 0, ',', '.');

        return "
        <div style='font-family:Georgia,serif;max-width:600px;margin:auto;background:#fff;border:1px solid #e8ddc9;'>
            <div style='background:#1a1610;padding:30px;text-align:center;'>
                <h1 style='color:#fff;font-size:28px;font-weight:300;margin:0;letter-spacing:4px;'>LUMIÈRE</h1>
                <p style='color:#b08f5f;font-size:11px;letter-spacing:3px;margin:6px 0 0;'>HOTELS</p>
            </div>
            <div style='padding:40px;'>
                <p style='color:#b08f5f;font-size:11px;letter-spacing:3px;text-transform:uppercase;'>Confirmación de Reserva</p>
                <h2 style='color:#1a1610;font-size:22px;font-weight:300;margin:8px 0 24px;'>Hola, {$nombre}</h2>
                <p style='color:#5a4a3a;line-height:1.8;margin-bottom:28px;'>
                    Tu reserva ha sido registrada exitosamente. Aquí tienes los detalles:
                </p>
                <table style='width:100%;border-collapse:collapse;font-size:14px;'>
                    <tr style='background:#1a1610;'>
                        <td style='color:#fff;padding:12px 16px;font-size:11px;letter-spacing:2px;text-transform:uppercase;'>Campo</td>
                        <td style='color:#fff;padding:12px 16px;font-size:11px;letter-spacing:2px;text-transform:uppercase;'>Detalle</td>
                    </tr>
                    <tr style='background:#faf8f4;'><td style='padding:10px 16px;color:#8b7355;'>Habitación</td><td style='padding:10px 16px;color:#1a1610;'>N° {$r['num_habitacion']}</td></tr>
                    <tr><td style='padding:10px 16px;color:#8b7355;'>Categoría</td><td style='padding:10px 16px;color:#1a1610;'>{$r['categoria']}</td></tr>
                    <tr style='background:#faf8f4;'><td style='padding:10px 16px;color:#8b7355;'>Fecha inicio</td><td style='padding:10px 16px;color:#1a1610;'>{$fechaInicio}</td></tr>
                    <tr><td style='padding:10px 16px;color:#8b7355;'>Fecha fin</td><td style='padding:10px 16px;color:#1a1610;'>{$fechaFin}</td></tr>
                    <tr style='background:#faf8f4;'><td style='padding:10px 16px;color:#8b7355;'>Personas</td><td style='padding:10px 16px;color:#1a1610;'>{$r['num_personas']}</td></tr>
                    <tr><td style='padding:10px 16px;color:#8b7355;font-weight:bold;'>Precio total</td><td style='padding:10px 16px;color:#1a1610;font-weight:bold;'>{$precio}</td></tr>
                </table>
            </div>
            <div style='background:#f5f1ea;padding:20px;text-align:center;'>
                <p style='color:#8b7355;font-size:11px;margin:0;'>© 2026 Lumière Hotels. Todos los derechos reservados.</p>
            </div>
        </div>";
    }
}
