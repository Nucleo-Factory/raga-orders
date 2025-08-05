<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Orden de Compra</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .content { background-color: #ffffff; padding: 20px; border: 1px solid #dee2e6; border-radius: 5px; }
        .footer { margin-top: 20px; padding: 20px; background-color: #f8f9fa; border-radius: 5px; font-size: 14px; color: #6c757d; }
        .button { display: inline-block; padding: 12px 24px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .details { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .detail-row { display: flex; justify-content: space-between; margin: 5px 0; }
        .label { font-weight: bold; }
        .value { color: #495057; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Confirmación de Orden de Compra</h2>
        </div>

        <div class="content">
            <p>{{ $emailSettings['greeting'] }}</p>

            <p>{{ $emailSettings['body'] }}</p>

            <div class="details">
                <div class="detail-row">
                    <span class="label">Número de Orden:</span>
                    <span class="value">{{ $po->order_number }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Fecha de Entrega:</span>
                    <span class="value">{{ $po->getFormattedDeliveryDateAttribute() }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Total:</span>
                    <span class="value">{{ $po->currency }} {{ number_format($po->total, 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Proveedor:</span>
                    <span class="value">{{ $po->vendor->name ?? 'N/A' }}</span>
                </div>
            </div>

            <p><strong>Para confirmar esta orden de compra, haga clic en el siguiente enlace:</strong></p>

            <a href="{{ $confirmationUrl }}" class="button">Confirmar Orden de Compra</a>

            <p><strong>Importante:</strong> Este enlace expira en {{ $expiryHours }} horas.</p>

            <p>{{ $emailSettings['footer'] }}</p>
        </div>

        <div class="footer">
            <p>Este es un email automático del sistema de confirmación de órdenes de compra.</p>
            <p>Si no puede hacer clic en el botón, copie y pegue este enlace en su navegador:</p>
            <p style="word-break: break-all; font-size: 12px; color: #6c757d;">{{ $confirmationUrl }}</p>
        </div>
    </div>
</body>
</html>
